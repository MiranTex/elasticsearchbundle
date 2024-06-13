pimcore.registerNS("pimcore.plugin.BundlesElasticsearchBundle");

pimcore.plugin.BundlesElasticsearchBundle = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.pimcoreReady, this.pimcoreReady.bind(this));
    },

    pimcoreReady: function (e) {
        // alert("BundlesElasticsearchBundle ready!");
    }
});

var BundlesElasticsearchBundlePlugin = new pimcore.plugin.BundlesElasticsearchBundle();
