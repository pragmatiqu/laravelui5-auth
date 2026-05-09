sap.ui.define(["./BaseController", "com/laravelui5/core/LaravelUi5", "sap/ui/model/json/JSONModel"], function (__BaseController, __LaravelUi5, JSONModel) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const BaseController = _interopRequireDefault(__BaseController);
  const LaravelUi5 = _interopRequireDefault(__LaravelUi5);
  const THROTTLE_PATTERN = /^auth_throttle\|(\d+)\|(\d+)$/;

  /**
   * @namespace io.pragmatiqu.auth.controller
   */
  const ForgotPassword = BaseController.extend("io.pragmatiqu.auth.controller.ForgotPassword", {
    onInit: function _onInit() {
      this.setModel(new JSONModel({
        submitting: false,
        result: {
          visible: false,
          type: "None",
          text: ""
        }
      }), "forgotPassword");
      this.getView().addEventDelegate({
        onAfterShow: () => {
          setTimeout(() => this.byId("emailInput").focus(), 0);
        }
      });
    },
    onContinue: async function _onContinue() {
      const login = this.getModel("login");
      const state = this.getModel("forgotPassword");
      const bundle = await this.getResourceBundle();
      state.setProperty("/submitting", true);
      try {
        await LaravelUi5.post("/auth/forgot-password", {
          email: login.getProperty("/email")
        });
        this.setStrip(state, "Success", bundle.getText("forgotPassword.success") ?? "");
      } catch (error) {
        const seconds = this.parseThrottleSeconds(error);
        if (seconds !== null) {
          const text = bundle.getText("forgotPassword.throttle", [seconds]) ?? "";
          this.setStrip(state, "Error", text);
          return;
        }
        // Fold validation / network / server errors into the success strip
        // to preserve email-enumeration uniformity.
        this.setStrip(state, "Success", bundle.getText("forgotPassword.success") ?? "");
      } finally {
        state.setProperty("/submitting", false);
      }
    },
    setStrip: function _setStrip(state, type, text) {
      state.setProperty("/result", {
        visible: true,
        type,
        text
      });
    },
    parseThrottleSeconds: function _parseThrottleSeconds(error) {
      const messages = error.cause?.errors?.email;
      if (!messages || messages.length === 0) {
        return null;
      }
      const match = THROTTLE_PATTERN.exec(messages[0]);
      return match ? parseInt(match[1], 10) : null;
    }
  });
  return ForgotPassword;
});
//# sourceMappingURL=ForgotPassword-dbg.controller.js.map
