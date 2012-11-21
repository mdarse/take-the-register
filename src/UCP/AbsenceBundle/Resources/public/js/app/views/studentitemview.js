App.Views.StudentItemView = Backbone.View.extend({

    initialize: function() {
        this.model.on('change', this.render, this);
    },

    render: function() {
        var firstname = this.model.get('firstname');
        var lastname =  this.model.get('lastname');
        this.$el.html(firstname + ' ' + lastname);
        return this;
    }

});