@extends('administrator')
    @section('contenido')
    @if (Auth::check())
        @if (Auth::user()->idrol == 1)
            <template v-if="menu==0">
                <dashboard-component></dashboard-component>
            </template> 
            <template v-if="menu==1">
                <categoria-component></categoria-component>
            </template>   
            <template v-if="menu==2">
                <articulo-component></articulo-component>
            </template>
            <template v-if="menu==3">
                <ingreso-component></ingreso-component>
            </template>
            <template v-if="menu==4">
                <proveedor-component></proveedor-component>
            </template>
            <template v-if="menu==13">
            <produccion-component></produccion-component>
            </template>
            <template v-if="menu==14">
            <receta-component></receta-component>
            </template>
            <template v-if="menu==5">
            <venta-component></venta-component>
            </template>
            <template v-if="menu==6">
                <cliente-component></cliente-component>
            </template>
            <template v-if="menu==7">
            <user-component></user-component>
            </template>
            <template v-if="menu==8">
            <rol-component></rol-component>
            </template>
            <template v-if="menu==9">
                <h1>Reporte de Ingresos</h1>
            </template>
            <template v-if="menu==10">
                <h1>Reporte de Ventas</h1>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>
            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>
        @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <dashboard-component></dashboard-component>
            </template> 
            <template v-if="menu==5">
            <venta-component></venta-component>
            </template>
            <template v-if="menu==6">
                <cliente-component></cliente-component>
            </template>
            <template v-if="menu==10">
                <h1>Reporte de Ventas</h1>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>
            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>  
        @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <dashboard-component></dashboard-component>
            </template> 
            <template v-if="menu==1">
                <categoria-component></categoria-component>
            </template>   
            <template v-if="menu==2">
                <articulo-component></articulo-component>
            </template>
            <template v-if="menu==3">
                <ingreso-component></ingreso-component>
            </template>
            <template v-if="menu==4">
                <proveedor-component></proveedor-component>
            </template>
            <template v-if="menu==13">
            <produccion-component></produccion-component>
            </template>
            <template v-if="menu==14">
            <receta-component></receta-component>
            <template v-if="menu==9">
                <h1>Reporte de Ingresos</h1>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>
            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>  
        @else

        @endif
    @endif           
    @endsection