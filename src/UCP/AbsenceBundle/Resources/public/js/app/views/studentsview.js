App.Views.StudentsView = Backbone.View.extend({

    initialize: function() {
        this.template = Handlebars.templates.students;
        _.bindAll(this);
    },

    events: {
        "click ul.student-list a": "clikedStudent"
    },

    render: function() {
        console.log("StudentsView:render");
        this.$el.html(this.template({ students: this.collection.toJSON() }));
        return this;
    },

    clikedStudent: function(e) {
        e.preventDefault();
        var id = $(e.currentTarget).data("id");
        app.navigate("students/" + id);
        this.show(id);
    },

    show: function(id) {
        console.log("StudentsView:show", id);
    }

});
