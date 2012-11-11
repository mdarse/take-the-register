App.Views.StudentsView = Backbone.View.extend({

    options: {
        detailSelector: ".student-details"
    },

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
        this.emptyDetailsEl = this.$(this.options.detailSelector).get(0);
        if (this.currentDetailView) {
            this.$(this.options.detailSelector).replaceWith(this.currentDetailView.el);
        }
        return this;
    },

    clikedStudent: function(e) {
        e.preventDefault();
        var id = $(e.currentTarget).data("id");
        app.navigate("students/" + id);
        this.show(id);
    },

    show: function(id) {
        var student = this.collection.get(id);
        if (!student) {
            student = new App.Models.Student({ id: id });
            this.collection.add(student);
            student.fetch();
        }
        var view = new App.Views.StudentDetailsView({ model: student });
        view.render();
        this.changeDetailView(view);
    },

    reset: function() {
        this.changeDetailView(null);
    },

    changeDetailView: function(view) {
        this.currentDetailView = view;
        var el = view ? view.el : this.emptyDetailsEl;
        this.$(this.options.detailSelector).replaceWith(el);
    }

});
