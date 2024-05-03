// let evenements = [{
//     "title": "Live coding - Demo",
//     "start": "2019-11-23 09:00:00",
//     "end": "2019-11-23 11:00:00",
//     "backgroundColor": "#839c49"
// },{
//     "title": "Live coding - Demo 2",
//     "start": "2019-11-23 14:00:00",
//     "end": "2019-11-23 16:00:00"
// }]



window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier")
    let calendrier = new FullCalendar.Calendar(elementCalendrier,{
        plugins: ['dayGrid','timeGrid','list','interaction'],
        defaultView: 'timeGridWeek',
        locale: 'fr',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,list'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },

    })
    calendrier.render()

}