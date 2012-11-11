$(function() {
    app = new App.Router();
    Backbone.history.start({
        pushState: false,
        root: Routing.getBaseUrl() + '/'
    });
});
