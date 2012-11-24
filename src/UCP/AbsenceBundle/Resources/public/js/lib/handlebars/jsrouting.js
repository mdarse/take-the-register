// Genererate URL using FOSJsRouting
Handlebars.registerHelper('js_routing', function(options) {
    return Routing.generate(options.hash.route, options.hash.params);
});