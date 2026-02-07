sap.ui.define(["./BaseController", "sap/ui/model/json/JSONModel", "sap/m/MessageBox"], function (__BaseController, JSONModel, MessageBox) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const BaseController = _interopRequireDefault(__BaseController);
  /**
   * @namespace io.pragmatiqu.auth.controller
   */
  const Login = BaseController.extend("io.pragmatiqu.auth.controller.Login", {
    onInit: function _onInit() {
      const component = this.getOwnerComponent();
      const meta = component.getManifestEntry("/laravel.ui5/meta");
      const model = new JSONModel({
        email: null,
        password: null,
        meta: meta
      });
      this.setModel(model, "login");
    },
    onLogin: async function _onLogin() {
      MessageBox.error("So isses!");
    }
  });
  return Login;
});
//# sourceMappingURL=Login-dbg.controller.js.map
