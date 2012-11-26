App.Models.AbsenceCollection = Backbone.Collection.extend({

    model: App.Models.Absence,

    url: Routing.generate('get_absences')

});
