App.Views.NavBarView = Backbone.View.extend({

    render: function() {
        var html = Handlebars.templates.navbar();
        this.$el.html(html);
    }
});