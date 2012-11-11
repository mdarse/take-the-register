App.Views.StudentDetailsView = Backbone.View.extend({

    className: 'student-details',

    initialize: function() {
        this.template = Handlebars.templates.studentdetails;

        this.model.on('change', this.render, this);
    },

    render: function() {
        console.log("StudentDetailsView:render");
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    }

});
