sap.ui.define(["./BaseController", "sap/m/MessageBox", "com/laravelui5/core/LaravelUi5", "sap/m/library"], function (__BaseController, MessageBox, __LaravelUi5, sap_m_library) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const BaseController = _interopRequireDefault(__BaseController);
  const LaravelUi5 = _interopRequireDefault(__LaravelUi5);
  const URLHelper = sap_m_library["URLHelper"];
  /**
   * @namespace io.pragmatiqu.auth.controller
   */
  const Login = BaseController.extend("io.pragmatiqu.auth.controller.Login", {
    onInit: function _onInit() {
      this.getView().addEventDelegate({
        onAfterShow: () => {
          setTimeout(() => this.byId("emailInput").focus(), 0);
        }
      });
    },
    onLogin: async function _onLogin() {
      // Read credentials directly from the Input controls rather than the
      // shared `login` JSONModel. The model is a downstream cache, and
      // browser autofill / focus-loss timing can leave it stale even
      // with `valueLiveUpdate="true"` on the inputs (autofill events
      // some browsers swallow, model-vs-DOM races on submit). The
      // control's getValue() is the displayed value — i.e. what the
      // user actually sees in the field — so it's the source of truth
      // for what they intend to submit.
      const login = this.getModel("login");
      const payload = {
        email: this.byId("emailInput").getValue(),
        password: this.byId("passwordInput").getValue(),
        keepSignedIn: login.getProperty("/keepSignedIn")
      };
      try {
        const response = await LaravelUi5.call("io.pragmatiqu.auth.actions.login", {}, payload);
        URLHelper.redirect(response.redirect, false);
      } catch (error) {
        const err = error;
        MessageBox.error(err.cause.message, {
          title: err.statusText
        });
      }
    }
  });
  return Login;
});
//# sourceMappingURL=Login-dbg.controller.js.map
