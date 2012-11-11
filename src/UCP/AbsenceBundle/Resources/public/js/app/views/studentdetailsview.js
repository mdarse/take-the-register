App.Views.StudentDetailsView = Backbone.View.extend({

    className: 'student-details',

    initialize: function() {
        this.template = Handlebars.templates.studentdetails;

        this.model.on('change', this.render, this);
    },

    events: {
        "click a.flip-button": "flip"
    },

    render: function() {
        console.log("StudentDetailsView:render");
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    },

    flip: function(e) {
        e.preventDefault();
        this.$el.toggleClass('flip');
    }

});
