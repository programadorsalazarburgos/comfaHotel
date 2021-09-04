var app = new Vue({
  el: '#vueapp',
  data: {
    nombre: 'johan',
    telefono: '',
    regs: []
    },
  methods: {

    reloadList: function() {
        console.log(33);
        },

    },
  created: function() {
    alert(2);
    this.reloadList();
    }
});
