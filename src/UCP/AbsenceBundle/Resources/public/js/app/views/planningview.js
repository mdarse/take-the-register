App.Views.PlanningView = Backbone.View.extend({

    initialize: function() {
        this.template = Handlebars.templates.planning;
    },

    render: function() {
        this.$el.html(this.template({ lessons: this.collection.toJSON() }));
        return this;
    }

});