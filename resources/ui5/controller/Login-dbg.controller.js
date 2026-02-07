sap.ui.define(["./BaseController", "sap/m/MessageBox", "com/laravelui5/core/LaravelUi5", "sap/ui/model/json/JSONModel", "sap/m/library"], function (__BaseController, MessageBox, __LaravelUi5, JSONModel, sap_m_library) {
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
      this.setModel(new JSONModel({
        email: null,
        password: null,
        keepSignedIn: false
      }), "login");
    },
    onLogin: async function _onLogin() {
      const login = this.getModel("login");
      try {
        const response = await LaravelUi5.call("io.pragmatiqu.auth.actions.login", {}, login.getData());
        URLHelper.redirect(response.redirect, false);
      } catch (error) {
        MessageBox.error(error.cause.message, {
          title: error.statusText
        });
      }
    }
  });
  return Login;
});
//# sourceMappingURL=Login-dbg.controller.js.map
