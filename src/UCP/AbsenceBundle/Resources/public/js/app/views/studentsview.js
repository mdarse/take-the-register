App.Views.StudentsView = Backbone.View.extend({

    options: {
        detailSelector: ".student-details"
    },

    initialize: function() {
        this.template = Handlebars.templates.students;

        this.collection.on('add change remove reset', this.render, this);
    },

    events: {
        "click ul.student-list a": "clickedStudent"
    },

    render: function() {
        this.$el.html(this.template({ students: this.collection.toJSON() }));
        if (this.currentDetailView) {
            this.$(this.options.detailSelector).replaceWith(this.currentDetailView.el);
        }
        return this;
    },

    clickedStudent: function(e) {
        e.preventDefault();
        this.$('ul.student-list li.active').removeClass('active');
        var target = $(e.currentTarget);
        target.closest('li').addClass('active');
        var id = target.data("id");
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
        this.setDetailView(view);
    },

    reset: function() {
        this.currentDetailView = null;
        this.render();
    },

    setDetailView: function(view) {
        this.currentDetailView = view;
        // Bypass this.render() to not re-render sidebar
        this.$(this.options.detailSelector).replaceWith(view.el);
    }

});
