/**
 * MBA/Master's landing — enquiry form validation
 *
 * The form ships with `novalidate`, so this module owns the UX:
 *  - required fields (name, email, phone) block submission when empty
 *  - an inline message appears below the offending field
 *  - the first invalid field is focused and scrolled into view
 *  - errors clear live as the user types
 *
 * Note: this is progressive enhancement only — the server-side
 * MbaMastersLandingEnquiryRequest rules remain the security layer.
 */
(function () {
  "use strict";

  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  var RULES = [
    {
      name: "name",
      validate: function (value) {
        return value ? "" : "Please enter your full name.";
      },
    },
    {
      name: "email",
      validate: function (value) {
        if (!value) return "Please enter your email address.";
        if (!EMAIL_RE.test(value)) return "Please enter a valid email address.";
        return "";
      },
    },
    {
      name: "phone",
      validate: function (value) {
        return value ? "" : "Please enter your phone or WhatsApp number.";
      },
    },
  ];

  function errorId(input) {
    return input.id ? input.id + "-error" : "";
  }

  function findErrorEl(label) {
    return label ? label.querySelector(".mlp-field__error") : null;
  }

  function describedByTokens(input) {
    return (input.getAttribute("aria-describedby") || "")
      .split(/\s+/)
      .filter(Boolean);
  }

  function showError(input, message) {
    var label = input.closest(".mlp-field");
    if (!label) return;

    label.classList.add("mlp-field--error");
    input.setAttribute("aria-invalid", "true");

    var error = findErrorEl(label);
    var id = errorId(input);

    if (!error) {
      error = document.createElement("p");
      error.className = "mlp-field__error";
      error.setAttribute("role", "alert");
      if (id) error.id = id;
      input.insertAdjacentElement("afterend", error);
    }
    error.textContent = message;

    if (id) {
      var tokens = describedByTokens(input);
      if (tokens.indexOf(id) === -1) {
        tokens.push(id);
        input.setAttribute("aria-describedby", tokens.join(" "));
      }
    }
  }

  function clearError(input) {
    var label = input.closest(".mlp-field");
    if (!label) return;

    label.classList.remove("mlp-field--error");
    input.removeAttribute("aria-invalid");

    var error = findErrorEl(label);
    if (error) error.remove();

    var id = errorId(input);
    if (id) {
      var tokens = describedByTokens(input).filter(function (token) {
        return token !== id;
      });
      if (tokens.length) {
        input.setAttribute("aria-describedby", tokens.join(" "));
      } else {
        input.removeAttribute("aria-describedby");
      }
    }
  }

  function bindForm(form) {
    if (form.dataset.mlpValidationBound === "1") return;
    form.dataset.mlpValidationBound = "1";

    var fields = RULES.map(function (rule) {
      return {
        rule: rule,
        input: form.querySelector('[name="' + rule.name + '"]'),
      };
    }).filter(function (field) {
      return !!field.input;
    });

    form.addEventListener("submit", function (event) {
      var firstInvalid = null;

      fields.forEach(function (field) {
        var message = field.rule.validate(field.input.value.trim());
        if (message) {
          showError(field.input, message);
          if (!firstInvalid) firstInvalid = field.input;
        } else {
          clearError(field.input);
        }
      });

      if (firstInvalid) {
        // Block the submit — nothing is sent until the fields are fixed.
        event.preventDefault();

        var viewportH =
          window.innerHeight || document.documentElement.clientHeight || 0;
        var rect = firstInvalid.getBoundingClientRect();
        var offscreen = rect.top < 0 || rect.bottom > viewportH;

        firstInvalid.focus({ preventScroll: true });
        if (offscreen) {
          firstInvalid.scrollIntoView({ block: "center", behavior: "smooth" });
        }
      }
    });

    // Live feedback: once an error is showing, re-validate while typing
    // so the message updates and clears as soon as the field is valid.
    fields.forEach(function (field) {
      field.input.addEventListener("input", function () {
        if (!field.input.closest(".mlp-field--error")) return;

        var message = field.rule.validate(field.input.value.trim());
        if (message) {
          showError(field.input, message);
        } else {
          clearError(field.input);
        }
      });
    });
  }

  function init() {
    document.querySelectorAll("form[data-mlp-enquiry]").forEach(bindForm);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
