App.Views.PlanningView = Backbone.View.extend({

    initialize: function() {
        this.template = Handlebars.templates.planning;
        this.collection.on('add change remove reset', this.render, this);
    },

    render: function() {
        this.$el.html(this.template({ lessons: this.collection.toJSON() }));
        return this;
    }

});