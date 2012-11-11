App.Views.StudentsView = Backbone.View.extend({

    initialize: function() {
        this.template = Handlebars.templates.students;
    },

    render: function() {
        this.$el.html(this.template());
        return this;
    },

    show: function(id) {
        console.log("StudentsView:show", id);
    }

});
