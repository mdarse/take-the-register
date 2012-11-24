App.Views.HeaderView = Backbone.View.extend({

    render: function() {
        var html = Handlebars.templates.header({
            username: this.options.username
        });
        this.$el.html(html);
        return this;
    },

    select: function(route) {
        // Remove all active items
        this.$('#navigation a').removeClass('active');
        // Mark active link(s) corresponding to route
        this.$('#navigation a[data-route="' + route + '"]').addClass('active');
    }
});