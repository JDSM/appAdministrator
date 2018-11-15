@extends('administrator')
    @section('contenido')
       
            
        <template v-if="menu==0">
            <h1>Contenido menu 0</h1>
        </template> 
        <template v-if="menu==1">
            <categoria-component></categoria-component>
        </template>   
        <template v-if="menu==2">
            <articulo-component></articulo-component>
        </template>
        <template v-if="menu==3">
            <h1>Contenido 3</h1>
        </template>
        <template v-if="menu==4">
            <proveedor-component></proveedor-component>
        </template>
        <template v-if="menu==5">
            <h1>Contenido 5</h1>
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
            <h1>Contenido 9</h1>
        </template>
        <template v-if="menu==10">
            <h1>Contenido 10</h1>
        </template>
        <template v-if="menu==11">
            <h1>Contenido 11</h1>
        </template>
        <template v-if="menu==12">
            <h1>Contenido 12</h1>
        </template>           
    @endsection