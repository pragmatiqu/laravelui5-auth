sap.ui.define(["sap/ui/core/UIComponent", "./model/models", "sap/ui/Device", "com/laravelui5/core/LaravelUi5", "sap/ui/model/json/JSONModel", "sap/m/library", "./model/IntentCatalog"], function (UIComponent, __models, Device, __LaravelUi5, JSONModel, sap_m_library, ___model_IntentCatalog) {
  "use strict";

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule && typeof obj.default !== "undefined" ? obj.default : obj;
  }
  const models = _interopRequireDefault(__models);
  const LaravelUi5 = _interopRequireDefault(__LaravelUi5);
  const URLHelper = sap_m_library["URLHelper"];
  const INTENT_CATALOG = ___model_IntentCatalog["INTENT_CATALOG"];
  /**
   * @namespace io.pragmatiqu.auth
   */
  const Component = UIComponent.extend("io.pragmatiqu.auth.Component", {
    metadata: {
      manifest: "json",
      interfaces: ["sap.ui.core.IAsyncContentCreation"]
    },
    init: function _init() {
      // call the base component's init function
      UIComponent.prototype.init.call(this);

      // create the device model
      this.setModel(models.createDeviceModel(), "device");
      this.setModel(new JSONModel({
        email: null,
        password: null,
        passwordConfirmation: null,
        token: null,
        keepSignedIn: false
      }), "login");
      this.setModel(new JSONModel({}), "intent");
      LaravelUi5.init(this).then(() => {
        this.getRouter().initialize();
      }).catch(error => {
        console.error(error);
      });
    },
    /**
     * Dispatches a serialized intent returned by the backend dispenser.
     *
     * Terminal intents (redirect) navigate the browser. Interactive intents
     * publish their payload to the "intent" model and route to their view.
     * Unknown kinds throw — the catalog is closed; an unknown kind here
     * means the backend version is ahead of the frontend version, which
     * is a versioning bug to land at dev/CI time, not in production.
     */
    dispatchIntent: function _dispatchIntent(intent) {
      const binding = INTENT_CATALOG[intent.kind];
      if (!binding) {
        console.error(`[ui5-auth] unknown intent kind: ${intent.kind}`);
        throw new Error(`unknown intent kind: ${intent.kind}`);
      }
      if (binding.terminal && intent.kind === "redirect") {
        URLHelper.redirect(intent.payload.target, false);
        return;
      }
      this.getModel("intent").setData(intent);
      this.getRouter().navTo(binding.view);
    },
    /**
     * This method can be called to determine whether the sapUiSizeCompact or sapUiSizeCozy
     * design mode class should be set, which influences the size appearance of some controls.
     * @public
     * @returns css class, either 'sapUiSizeCompact' or 'sapUiSizeCozy' - or an empty string if no css class should be set
     */
    getContentDensityClass: function _getContentDensityClass() {
      if (this.contentDensityClass === undefined) {
        // check whether FLP has already set the content density class; do nothing in this case
        if (document.body.classList.contains("sapUiSizeCozy") || document.body.classList.contains("sapUiSizeCompact")) {
          this.contentDensityClass = "";
        } else if (!Device.support.touch) {
          // apply "compact" mode if touch is not supported
          this.contentDensityClass = "sapUiSizeCompact";
        } else {
          // "cozy" in case of touch support; default for most sap.m controls, but needed for desktop-first controls like sap.ui.table.Table
          this.contentDensityClass = "sapUiSizeCozy";
        }
      }
      return this.contentDensityClass;
    }
  });
  return Component;
});
//# sourceMappingURL=Component-dbg.js.map
