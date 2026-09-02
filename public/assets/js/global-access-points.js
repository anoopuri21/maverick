/*
 * Maverick Access Points — transparent, draggable orthographic globe.
 * Country data is injected by the Blade section so an approved admin payload
 * can replace it later without changing the renderer.
 */
(function () {
  "use strict";

  var stage = document.querySelector("[data-gap-globe]");
  if (!stage) return;

  var canvas = stage.querySelector("#gap-globe");
  var visual = stage.querySelector(".gap-globe__visual");
  var countryButtons = stage.querySelectorAll("[data-gap-country]");
  var status = stage.querySelector("[data-gap-globe-status]");

  if (!canvas) return;

  var context = canvas.getContext("2d");
  if (!context) return;

  var countries = Array.isArray(window.globalAccessPointsCountries)
    ? window.globalAccessPointsCountries
    : [];
  var selectedIds = new Set(countries.map(function (country) {
    return String(country.id).padStart(3, "0");
  }));
  var reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var worldFeatures = [];
  var selectedFeatures = [];
  var selectedCenters = [];
  var projection = null;
  var path = null;
  var graticule = null;
  var width = 1;
  var height = 1;
  var radius = 1;
  var devicePixelRatio = 1;
  var frameId = null;
  var resizeObserver = null;
  var hoveredCountry = null;
  var activeCountryId = null;
  var isDragging = false;
  var pointerId = null;
  var dragStart = null;
  var lastPointer = null;
  var velocity = [0, 0];
  var lastInteraction = 0;
  var worldReady = false;
  var rotation = [-48, -20, 0];

  function clamp(value, min, max) {
    return Math.max(min, Math.min(max, value));
  }

  function countryId(feature) {
    return String(feature && feature.id).padStart(3, "0");
  }

  function updateStatus(message) {
    if (status) status.textContent = message;
  }

  function setupCanvas() {
    var rect = (visual || stage).getBoundingClientRect();
    width = Math.max(1, rect.width);
    height = Math.max(1, rect.height);
    devicePixelRatio = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.round(width * devicePixelRatio);
    canvas.height = Math.round(height * devicePixelRatio);
    canvas.style.width = width + "px";
    canvas.style.height = height + "px";
    context.setTransform(devicePixelRatio, 0, 0, devicePixelRatio, 0, 0);

    radius = Math.min(width, height) * 0.48;
    projection = window.d3
      .geoOrthographic()
      .translate([width / 2, height / 2])
      .scale(radius)
      .rotate(rotation)
      .clipAngle(90)
      .precision(0.45);
    path = window.d3.geoPath(projection, context);
    graticule = window.d3.geoGraticule().step([15, 15])();
  }

  function visibleCenter(coords, margin) {
    if (!window.d3 || !coords) return false;
    var viewCenter = projection && projection.invert
      ? projection.invert([width / 2, height / 2])
      : [-rotation[0], -rotation[1]];
    var visibilityMargin = typeof margin === "number" ? margin : 0;
    return window.d3.geoDistance(coords, viewCenter) <= Math.PI / 2 - visibilityMargin;
  }

  function projectedCenter(center, margin) {
    if (!visibleCenter(center.coords, margin)) return null;
    var point = projection(center.coords);
    if (!point || !Number.isFinite(point[0]) || !Number.isFinite(point[1])) return null;
    if (Math.hypot(point[0] - width / 2, point[1] - height / 2) > radius - 2) return null;
    return point;
  }

  function drawLocationPin(point, active) {
    var x = point[0];
    var y = point[1];
    context.save();
    context.beginPath();
    context.moveTo(x, y - 8);
    context.bezierCurveTo(x - 5.5, y - 8, x - 8.5, y - 4.2, x - 8.5, y + 0.2);
    context.bezierCurveTo(x - 8.5, y + 5.4, x - 3.1, y + 10.6, x, y + 14);
    context.bezierCurveTo(x + 3.1, y + 10.6, x + 8.5, y + 5.4, x + 8.5, y + 0.2);
    context.bezierCurveTo(x + 8.5, y - 4.2, x + 5.5, y - 8, x, y - 8);
    context.closePath();
    context.fillStyle = active ? "#b20202" : "rgba(15, 41, 131, 0.92)";
    context.fill();
    context.strokeStyle = "rgba(255, 255, 255, 0.96)";
    context.lineWidth = active ? 1.35 : 1;
    context.stroke();
    context.beginPath();
    context.arc(x, y - 1, active ? 2.5 : 2.1, 0, Math.PI * 2);
    context.fillStyle = "#ffffff";
    context.fill();
    context.restore();
  }

  function createLabelCandidate(point, alignLeft, offsetY) {
    context.font = "500 10px Poppins, sans-serif";
    var textWidth = context.measureText(point.country.name).width;
    var boxWidth = textWidth + 12;
    var boxHeight = 18;
    var labelX = point.point[0] + (alignLeft ? 15 : -15);
    var labelY = point.point[1] - 13 + offsetY;
    var boxLeft = alignLeft ? labelX - 5 : labelX - boxWidth + 5;
    var boxTop = labelY - 9;
    var edgePadding = 5;

    if (
      boxLeft < edgePadding ||
      boxLeft + boxWidth > width - edgePadding ||
      boxTop < edgePadding ||
      boxTop + boxHeight > height - edgePadding
    ) {
      return null;
    }

    return {
      country: point.country,
      point: point.point,
      active: point.active,
      alignLeft: alignLeft,
      labelX: labelX,
      labelY: labelY,
      boxLeft: boxLeft,
      boxTop: boxTop,
      boxWidth: boxWidth,
      boxHeight: boxHeight,
    };
  }

  function labelsOverlap(first, second) {
    var gap = 3;
    return first.boxLeft < second.boxLeft + second.boxWidth + gap
      && first.boxLeft + first.boxWidth + gap > second.boxLeft
      && first.boxTop < second.boxTop + second.boxHeight + gap
      && first.boxTop + first.boxHeight + gap > second.boxTop;
  }

  function placeCountryLabel(point, placedLabels) {
    var inwardAlignment = point.point[0] < width / 2;
    var alignments = [inwardAlignment, !inwardAlignment];
    var offsets = [0, -22, 22, -44, 44, -66, 66];

    for (var alignmentIndex = 0; alignmentIndex < alignments.length; alignmentIndex += 1) {
      for (var offsetIndex = 0; offsetIndex < offsets.length; offsetIndex += 1) {
        var candidate = createLabelCandidate(point, alignments[alignmentIndex], offsets[offsetIndex]);
        if (!candidate) continue;
        var overlaps = placedLabels.some(function (placedLabel) {
          return labelsOverlap(candidate, placedLabel);
        });
        if (!overlaps) return candidate;
      }
    }

    return null;
  }

  function drawCountryLabel(label) {
    context.font = "500 10px Poppins, sans-serif";
    context.textBaseline = "middle";
    context.textAlign = label.alignLeft ? "left" : "right";

    context.beginPath();
    context.moveTo(label.point[0], label.point[1] + 1);
    context.lineTo(label.labelX, label.labelY);
    context.strokeStyle = label.active ? "rgba(178, 2, 2, 0.74)" : "rgba(15, 41, 131, 0.36)";
    context.lineWidth = label.active ? 1.2 : 0.7;
    context.stroke();

    context.fillStyle = label.active ? "rgba(178, 2, 2, 0.12)" : "rgba(255, 255, 255, 0.8)";
    context.fillRect(label.boxLeft, label.boxTop, label.boxWidth, label.boxHeight);
    context.strokeStyle = label.active ? "rgba(178, 2, 2, 0.72)" : "rgba(15, 41, 131, 0.22)";
    context.lineWidth = 0.8;
    context.strokeRect(label.boxLeft, label.boxTop, label.boxWidth, label.boxHeight);
    context.fillStyle = label.active ? "#b20202" : "#071444";
    context.fillText(label.country.name, label.labelX, label.labelY);
  }

  function drawGlobe(now) {
    if (!projection || !path) return;

    context.clearRect(0, 0, width, height);

    var centerX = width / 2;
    var centerY = height / 2;
    var sphere = { type: "Sphere" };

    context.save();
    context.beginPath();
    path(sphere);
    var atmosphere = context.createRadialGradient(
      centerX - radius * 0.38,
      centerY - radius * 0.48,
      radius * 0.08,
      centerX,
      centerY,
      radius * 1.04,
    );
    atmosphere.addColorStop(0, "rgba(255, 255, 255, 0.24)");
    atmosphere.addColorStop(0.38, "rgba(15, 41, 131, 0.13)");
    atmosphere.addColorStop(0.82, "rgba(7, 20, 68, 0.08)");
    atmosphere.addColorStop(1, "rgba(7, 20, 68, 0.015)");
    context.fillStyle = atmosphere;
    context.fill();
    context.strokeStyle = "rgba(15, 41, 131, 0.26)";
    context.lineWidth = 1;
    context.stroke();

    context.beginPath();
    path(graticule);
    context.strokeStyle = "rgba(7, 20, 68, 0.18)";
    context.lineWidth = 0.7;
    context.stroke();

    selectedFeatures.forEach(function (feature) {
      var active = activeCountryId === countryId(feature);
      context.beginPath();
      path(feature);
      context.fillStyle = active ? "rgba(178, 2, 2, 0.88)" : "rgba(15, 41, 131, 0.2)";
      context.fill();
      context.strokeStyle = active ? "rgba(178, 2, 2, 0.98)" : "rgba(15, 41, 131, 0.62)";
      context.lineWidth = active ? 1.35 : 0.7;
      context.stroke();
    });

    var visibleLabels = [];
    selectedCenters.forEach(function (country) {
      var point = projectedCenter(country, 0.035);
      if (!point) return;

      var highlighted = activeCountryId === country.id || (hoveredCountry && hoveredCountry.id === country.id);
      var pulse = (Math.sin(now / 720 + country.index * 0.46) + 1) / 2;
      context.beginPath();
      context.arc(point[0], point[1], highlighted ? 12 + pulse * 4 : 9 + pulse * 2, 0, Math.PI * 2);
      context.strokeStyle = highlighted
        ? "rgba(178, 2, 2, " + (0.28 + pulse * 0.28) + ")"
        : "rgba(15, 41, 131, " + (0.12 + pulse * 0.1) + ")";
      context.lineWidth = highlighted ? 1.2 : 0.7;
      context.stroke();
      drawLocationPin(point, highlighted);

      var labelPoint = projectedCenter(country, 0.12);
      if (labelPoint) {
        visibleLabels.push({
          country: country,
          point: labelPoint,
          active: highlighted,
          distance: Math.hypot(labelPoint[0] - centerX, labelPoint[1] - centerY),
        });
      }
    });

    visibleLabels.sort(function (first, second) {
      if (first.active !== second.active) return first.active ? -1 : 1;
      return first.distance - second.distance;
    });

    var placedLabels = [];
    visibleLabels.forEach(function (point) {
      var label = placeCountryLabel(point, placedLabels);
      if (!label) return;
      placedLabels.push(label);
      drawCountryLabel(label);
    });

    context.restore();
  }

  function updateHover(event) {
    if (!worldReady || isDragging) return;
    var rect = canvas.getBoundingClientRect();
    var x = event.clientX - rect.left;
    var y = event.clientY - rect.top;
    var nearest = null;
    var nearestDistance = Infinity;

    selectedCenters.forEach(function (country) {
      var point = projectedCenter(country, 0.035);
      if (!point) return;
      var distance = Math.hypot(point[0] - x, point[1] - y);
      if (distance < nearestDistance) {
        nearestDistance = distance;
        nearest = country;
      }
    });

    var nextHovered = nearestDistance <= 24 ? nearest : null;
    if ((hoveredCountry && !nextHovered) || (nextHovered && (!hoveredCountry || nextHovered.id !== hoveredCountry.id))) {
      hoveredCountry = nextHovered;
      updateStatus(hoveredCountry ? hoveredCountry.name : "Grab the globe to explore");
    }
  }

  function setRotation(nextRotation) {
    rotation[0] = nextRotation[0];
    rotation[1] = clamp(nextRotation[1], -88, 88);
    rotation[2] = nextRotation[2] || 0;
    if (projection) projection.rotate(rotation);
  }

  function selectCountry(id) {
    var normalizedId = String(id).padStart(3, "0");
    var selected = selectedCenters.find(function (country) {
      return country.id === normalizedId;
    });

    activeCountryId = normalizedId;
    countryButtons.forEach(function (button) {
      var isActive = String(button.getAttribute("data-gap-country")).padStart(3, "0") === normalizedId;
      button.setAttribute("aria-pressed", isActive ? "true" : "false");
      button.classList.toggle("is-active", isActive);
    });

    velocity = [0, 0];
    lastInteraction = performance.now();
    if (selected) {
      setRotation([-selected.coords[0], -selected.coords[1], 0]);
      hoveredCountry = selected;
      updateStatus(selected.name + " selected");
    }
  }

  function resetGlobe() {
    velocity = [0, 0];
    activeCountryId = null;
    hoveredCountry = null;
    countryButtons.forEach(function (button) {
      button.setAttribute("aria-pressed", "false");
      button.classList.remove("is-active");
    });
    lastInteraction = performance.now();
    setRotation([-48, -20, 0]);
    if (projection) projection.scale(radius);
    updateStatus("Grab the globe to explore");
    canvas.focus({ preventScroll: true });
  }

  function onPointerDown(event) {
    if (event.pointerType === "mouse" && event.button !== 0) return;
    isDragging = true;
    pointerId = event.pointerId;
    canvas.setPointerCapture(pointerId);
    stage.classList.add("is-grabbing");
    dragStart = {
      x: event.clientX,
      y: event.clientY,
      rotation: rotation.slice(),
    };
    lastPointer = {
      x: event.clientX,
      y: event.clientY,
      time: performance.now(),
    };
    velocity = [0, 0];
    hoveredCountry = null;
    updateStatus("Release to let the globe drift");
    event.preventDefault();
  }

  function onPointerMove(event) {
    if (isDragging) {
      var now = performance.now();
      var elapsed = Math.max(16, now - lastPointer.time);
      var dx = event.clientX - dragStart.x;
      var dy = event.clientY - dragStart.y;
      var deltaX = event.clientX - lastPointer.x;
      var deltaY = event.clientY - lastPointer.y;
      var dragScale = 92 / Math.max(radius, 1);

      setRotation([
        dragStart.rotation[0] + dx * dragScale,
        dragStart.rotation[1] - dy * dragScale * 0.72,
        0,
      ]);
      velocity = [
        (deltaX * dragScale) / (elapsed / 16),
        (-deltaY * dragScale * 0.72) / (elapsed / 16),
      ];
      lastPointer = { x: event.clientX, y: event.clientY, time: now };
      event.preventDefault();
      return;
    }

    updateHover(event);
  }

  function endPointer(event) {
    if (!isDragging) return;
    isDragging = false;
    stage.classList.remove("is-grabbing");
    lastInteraction = performance.now();
    if (pointerId !== null && canvas.hasPointerCapture(pointerId)) {
      canvas.releasePointerCapture(pointerId);
    }
    pointerId = null;
    updateStatus("Grab the globe to explore");
    if (reducedMotion) velocity = [0, 0];
    if (event) event.preventDefault();
  }

  function onKeyDown(event) {
    var step = event.shiftKey ? 14 : 7;
    if (event.key === "ArrowLeft") {
      setRotation([rotation[0] - step, rotation[1], 0]);
      lastInteraction = performance.now();
      event.preventDefault();
    } else if (event.key === "ArrowRight") {
      setRotation([rotation[0] + step, rotation[1], 0]);
      lastInteraction = performance.now();
      event.preventDefault();
    } else if (event.key === "ArrowUp") {
      setRotation([rotation[0], rotation[1] - step * 0.7, 0]);
      lastInteraction = performance.now();
      event.preventDefault();
    } else if (event.key === "ArrowDown") {
      setRotation([rotation[0], rotation[1] + step * 0.7, 0]);
      lastInteraction = performance.now();
      event.preventDefault();
    } else if (event.key === "Home") {
      resetGlobe();
      event.preventDefault();
    }
  }

  function animate(now) {
    if (projection && !isDragging) {
      var canAutoRotate = !reducedMotion && now - lastInteraction > 1800;
      if (Math.abs(velocity[0]) > 0.002 || Math.abs(velocity[1]) > 0.002) {
        setRotation([rotation[0] + velocity[0], rotation[1] + velocity[1], 0]);
        velocity[0] *= 0.94;
        velocity[1] *= 0.94;
      } else if (canAutoRotate && !hoveredCountry && !activeCountryId) {
        setRotation([rotation[0] + 0.035, rotation[1], 0]);
      }
    }

    drawGlobe(now);
    frameId = window.requestAnimationFrame(animate);
  }

  async function loadWorld() {
    if (!window.d3 || !window.topojson) return false;
    var urls = [
      "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-50m.json",
      "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json",
    ];

    for (var i = 0; i < urls.length; i += 1) {
      try {
        var response = await window.d3.json(urls[i]);
        if (response && response.objects && response.objects.countries) {
          worldFeatures = window.topojson.feature(response, response.objects.countries).features;
          selectedFeatures = worldFeatures.filter(function (feature) {
            return selectedIds.has(countryId(feature));
          });
          selectedCenters = selectedFeatures.map(function (feature, index) {
            var match = countries.find(function (country) {
              return String(country.id).padStart(3, "0") === countryId(feature);
            });
            return {
              id: countryId(feature),
              name: match ? match.name : "Selected country",
              coords: window.d3.geoCentroid(feature),
              index: index,
            };
          });
          if (activeCountryId) selectCountry(activeCountryId);
          worldReady = selectedFeatures.length > 0;
          stage.classList.toggle("is-ready", worldReady);
          stage.classList.toggle("is-error", !worldReady);
          updateStatus(worldReady ? "Grab the globe to explore" : "Country globe unavailable");
          return worldReady;
        }
      } catch (error) {
        // Try the lower-resolution fallback dataset.
      }
    }

    stage.classList.add("is-error");
    updateStatus("Country globe unavailable");
    return false;
  }

  function boot(retries) {
    if (!window.d3 || !window.topojson) {
      if (retries <= 0) {
        stage.classList.add("is-error");
        updateStatus("Country globe unavailable");
        return;
      }
      window.setTimeout(function () { boot(retries - 1); }, 80);
      return;
    }

    setupCanvas();
    if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
    loadWorld();
    frameId = window.requestAnimationFrame(animate);
  }

  canvas.addEventListener("pointerdown", onPointerDown);
  canvas.addEventListener("pointermove", onPointerMove);
  canvas.addEventListener("pointerup", endPointer);
  canvas.addEventListener("pointercancel", endPointer);
  canvas.addEventListener("pointerleave", function () {
    if (!isDragging) {
      hoveredCountry = null;
      updateStatus("Grab the globe to explore");
    }
  });
  canvas.addEventListener("keydown", onKeyDown);
  countryButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      selectCountry(button.getAttribute("data-gap-country"));
    });
  });

  if (window.ResizeObserver) {
    resizeObserver = new ResizeObserver(function () {
      setupCanvas();
    });
    resizeObserver.observe(stage);
  } else {
    window.addEventListener("resize", setupCanvas, { passive: true });
  }

  window.addEventListener("beforeunload", function () {
    if (frameId) window.cancelAnimationFrame(frameId);
    if (resizeObserver) resizeObserver.disconnect();
  }, { once: true });

  boot(100);
})();
