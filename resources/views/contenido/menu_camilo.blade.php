        <template v-if="menu==10">
            <tipohabitaciones></tipohabitaciones>
        </template>

        <template v-if="menu==20">
            <habitaciones></habitaciones>
        </template>
        <template v-if="menu==22">
            <turnos></turnos>
        </template>
        <template v-if="menu==24">
            <exchangerate></exchangerate>
        </template>
        <template v-if="menu==25">
            <reservas></reservas>
        </template>
        <template v-if="menu==26">
            <configuracion></configuracion>
        </template>
        <template v-if="menu==27">
            <impuestos></impuestos>
        </template>
        <template v-if="menu==28">
            <hoteles></hoteles>
        </template>
        <template v-if="menu==-1">
            <reservasreload></reservasreload>
        </template>
        <template v-if="menu==29">
        <estados></estados>
        </template>
        