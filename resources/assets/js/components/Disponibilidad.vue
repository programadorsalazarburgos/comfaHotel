<template>
    <main class="main">
        <!-- Breadcrumb -->    
        <section id="configuration">
          
        </section>
    </main>
</template>

<script>


import VueSweetAlert from 'vue-sweetalert'
import swal from "sweetalert2"
import Vue from 'vue'


export default {
    data() {
        return {
      
      
        };
    },

 watch: {
    selectedUsers: function (newVal, oldVal) {
      // Do what you want with the selected objects:
      console.log(newVal)
    }
  },
    
    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },
    methods: {
         

   

       
    },
    mounted() {
        this.base=base;
        this.listarRol(1, this.buscar, this.criterio);
    },
};
</script>
<style>
.modal-content {
    width: 100% !important;
    position: absolute !important;
}
.mostrar {
    display: block !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
    height: 100vh;
}
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
}

@media (max-width: 768px) {
    .mostrar {
        height: 150%;
    }
}





</style>
