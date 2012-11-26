App.Views.AbsencesView = Backbone.View.extend({

    render: function() {
        this.$el.empty();
        this.$el.html("Hello World");

        this.$el.append("truc");

        return this;
    }
});