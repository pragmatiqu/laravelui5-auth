sap.ui.define([], function () {
  "use strict";

  /**
   * Frontend catalog of post-login intents.
   *
   * The closed catalog is owned by the auth module: every kind the backend
   * can dispense has an entry here. Unknown kinds returned by the dispenser
   * are dev-time errors (see Component.dispatchIntent).
   *
   * Keep in sync with LaravelUi5\Auth\Intents\IntentKind on the backend.
   */

  const INTENT_CATALOG = {
    org_setup: {
      view: "OrgSetup",
      endpoint: "/auth/intents/org-setup"
    },
    redirect: {
      terminal: true
    }
  };
  var __exports = {
    __esModule: true
  };
  __exports.INTENT_CATALOG = INTENT_CATALOG;
  return __exports;
});
//# sourceMappingURL=IntentCatalog-dbg.js.map
