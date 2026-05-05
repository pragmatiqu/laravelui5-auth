sap.ui.define(["./BaseController", "com/laravelui5/core/LaravelUi5", "sap/ui/model/json/JSONModel"], function (__BaseController, __LaravelUi5, JSONModel) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const BaseController = _interopRequireDefault(__BaseController);
  const LaravelUi5 = _interopRequireDefault(__LaravelUi5);
  /**
   * @namespace io.pragmatiqu.auth.controller
   */
  const SetPassword = BaseController.extend("io.pragmatiqu.auth.controller.SetPassword", {
    onInit: function _onInit() {
      this.setModel(new JSONModel({
        submitting: false,
        done: false,
        result: {
          visible: false,
          type: "None",
          text: ""
        }
      }), "setPassword");
      const route = this.getRouter().getRoute("SetPassword");
      route?.attachPatternMatched(event => this.onRouteMatched(event));
    },
    onRouteMatched: function _onRouteMatched(event) {
      const args = event.getParameter("arguments");
      const login = this.getModel("login");
      login.setProperty("/token", args.token ?? "");
      login.setProperty("/email", args.email ?? "");
      login.setProperty("/password", "");
      login.setProperty("/passwordConfirmation", "");
      const state = this.getModel("setPassword");
      state.setProperty("/done", false);
      state.setProperty("/result", {
        visible: false,
        type: "None",
        text: ""
      });
    },
    onContinue: async function _onContinue() {
      const login = this.getModel("login");
      const state = this.getModel("setPassword");
      const bundle = await this.getResourceBundle();
      const payload = {
        token: login.getProperty("/token"),
        email: login.getProperty("/email"),
        password: login.getProperty("/password"),
        password_confirmation: login.getProperty("/passwordConfirmation")
      };
      state.setProperty("/submitting", true);
      try {
        await LaravelUi5.call("io.pragmatiqu.auth.actions.reset-password", {}, payload);
        // Drop sensitive fields the moment the reset succeeds. The shared
        // `login` model is consumed by the Login view too — leaving the
        // new password in there would pre-fill the sign-in form with it.
        login.setProperty("/password", "");
        login.setProperty("/passwordConfirmation", "");
        login.setProperty("/token", "");
        state.setProperty("/done", true);
        this.setStrip(state, "Success", bundle.getText("setPassword.success") ?? "");
      } catch (error) {
        const err = error;
        const text = this.diagnoseFailure(err) ? bundle.getText("setPassword.passwordError") ?? "" : bundle.getText("setPassword.failed") ?? "";
        this.setStrip(state, "Error", text);
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
    /**
     * Returns true if the failure is a password validation error (user can fix
     * by adjusting their input), false if it's a token/email/expired failure
     * (user must request a new reset link).
     */
    diagnoseFailure: function _diagnoseFailure(error) {
      const errors = error.cause?.errors;
      return Boolean(errors?.password && errors.password.length > 0);
    }
  });
  return SetPassword;
});
//# sourceMappingURL=SetPassword-dbg.controller.js.map
