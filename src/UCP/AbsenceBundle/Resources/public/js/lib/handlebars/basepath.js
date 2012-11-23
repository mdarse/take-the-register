// Gives access to App.basePath from templates
Handlebars.registerHelper('basePath', function() {
  return '/' + window.App.basePath;
});