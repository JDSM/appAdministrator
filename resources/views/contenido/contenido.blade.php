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
            <h1>Contenido 4</h1>
        </template>
        <template v-if="menu==5">
            <h1>Contenido 5</h1>
        </template>
        <template v-if="menu==6">
            <h1>Contenido 6</h1>
        </template>
        <template v-if="menu==7">
            <h1>Contenido 7</h1>
        </template>
        <template v-if="menu==8">
            <h1>Contenido 8</h1>
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