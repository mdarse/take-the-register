App.Views.StudentTileView = Backbone.View.extend({

    tagName: 'article',
    className: 'student tile',

    initialize: function() {
        this.template = Handlebars.templates['student-tile'];
        this.model.on('change', this.render, this);
        this.model.on('destroy', this.remove, this);
    },

    events: {
        'click': 'toggleMissing'
    },

    render: function() {
        var html = this.template( this.model.toJSON() );
        this.$el.html(html);
        return this;
    },

    toggleMissing: function() {
        this.$el.toggleClass('missing');
    }

});