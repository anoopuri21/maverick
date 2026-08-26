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
    var rect = stage.getBoundingClientRect();
    width = Math.max(1, rect.width);
    height = Math.max(1, rect.height);
    devicePixelRatio = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.round(width * devicePixelRatio);
    canvas.height = Math.round(height * devicePixelRatio);
    canvas.style.width = width + "px";
    canvas.style.height = height + "px";
    context.setTransform(devicePixelRatio, 0, 0, devicePixelRatio, 0, 0);

    radius = Math.min(width, height) * 0.42;
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

  function visibleCenter(coords) {
    if (!window.d3 || !coords) return null;
    var viewCenter = [-rotation[0], -rotation[1]];
    return window.d3.geoDistance(coords, viewCenter) < Math.PI / 2;
  }

  function projectedCenter(center) {
    if (!visibleCenter(center.coords)) return null;
    var point = projection(center.coords);
    if (!point || !Number.isFinite(point[0]) || !Number.isFinite(point[1])) return null;
    return point;
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

    selectedCenters.forEach(function (country) {
      var point = projectedCenter(country);
      if (!point) return;

      var active = activeCountryId === country.id;
      var pulse = (Math.sin(now / 720 + country.index * 0.46) + 1) / 2;
      context.beginPath();
      context.arc(point[0], point[1], active ? 5 + pulse * 5 : 3.4 + pulse * 2.2, 0, Math.PI * 2);
      context.strokeStyle = active
        ? "rgba(178, 2, 2, " + (0.38 + pulse * 0.3) + ")"
        : "rgba(15, 41, 131, " + (0.16 + pulse * 0.12) + ")";
      context.lineWidth = active ? 1.4 : 0.8;
      context.stroke();

      context.beginPath();
      context.arc(point[0], point[1], active ? 3.3 : 2.1, 0, Math.PI * 2);
      context.fillStyle = active ? "#b20202" : "rgba(15, 41, 131, 0.86)";
      context.fill();
      context.strokeStyle = "rgba(255, 255, 255, 0.9)";
      context.lineWidth = active ? 1.5 : 1.1;
      context.stroke();
    });

    if (hoveredCountry) {
      var hoveredPoint = projectedCenter(hoveredCountry);
      if (hoveredPoint) {
        var labelX = hoveredPoint[0] + (hoveredPoint[0] >= centerX ? 18 : -18);
        var labelY = hoveredPoint[1] - 18;
        var align = hoveredPoint[0] >= centerX ? "left" : "right";

        context.beginPath();
        context.moveTo(hoveredPoint[0], hoveredPoint[1]);
        context.lineTo(labelX, labelY);
        context.strokeStyle = "rgba(178, 2, 2, 0.72)";
        context.lineWidth = 1;
        context.stroke();

        context.font = "600 11px Poppins, sans-serif";
        context.textAlign = align;
        context.textBaseline = "middle";
        var labelWidth = context.measureText(hoveredCountry.name).width + 18;
        var boxLeft = align === "left" ? labelX - 8 : labelX - labelWidth + 8;
        context.fillStyle = "rgba(255, 255, 255, 0.94)";
        context.fillRect(boxLeft, labelY - 11, labelWidth, 22);
        context.strokeStyle = "rgba(178, 2, 2, 0.74)";
        context.strokeRect(boxLeft, labelY - 11, labelWidth, 22);
        context.fillStyle = "#071444";
        context.fillText(hoveredCountry.name, labelX, labelY);
      }
    }

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
      var point = projectedCenter(country);
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
