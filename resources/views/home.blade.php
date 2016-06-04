@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col s12">
        <h1>Dashboard</h1>
    </div>
</div>
<div class="row" id="character_panel">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">My Characters</span>
                <a href="{{ $eve_uri }}" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                <table>
                    <thead>
                        <tr>
                            <th>Monitor</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="character in characters">
                            <td>
                                <p>
                                    <input v-on:click="update(character)" type="checkbox" id="@{{ character.id }}" name="@{{ character.id }}" value="on" v-model="character.checked">
                                    <label for="@{{ character.id }}"></label>
                                </p>
                            </td>
                            <td>@{{ character.id }}</td>
                            <td>@{{ character.name }}</td>
                            <td>@{{ character.human_date }}</td>
                            <td><a class="waves-effect waves-light btn red" v-on:click="remove(character)"><i class="material-icons">delete</i></a></td>
                        </tr>
                        <tr v-show="characters.length == 0">
                            <td class="center-align" colspan="5"><em>You don't have any characters registered :(</em></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row" id="notification_panel">
    <div class="col s6">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Notification Settings</span>
                <div class="alert red darken-1" v-show="!use_mobile">
                    <h4>No mobile number</h4>
                    <p>You have no mobile number set, therefore we cannot text you if your character is found offline :/</p>
                </div>
                <div class="row">
                    <form class="col s12" v-on:submit.prevent="update">
                        <div class="row">
                            <div class="input-field col s12">
                                <input v-model="mobile_phone" placeholder="+1 415 570 12045" id="mobile_phone" type="text" class="validate">
                                <label for="mobile_phone">Mobile Phone</label>
                            </div>
                        </div>
                        <div class="row center-align">
                            <button type="submit" class="waves-effect waves-light btn blue">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    var cha_vm = new Vue({
        el: '#character_panel',
        data: {
            characters: [],
        },
        ready: function () {
            this.$http.get('/characters').then(function (response) {
                this.$set('characters', response.data);
            });
        },
        methods: {
            update: function (character) {
                this.$http.patch('/characters/' + character.id, { character }).then(function (resp) {
                    Materialize.toast('Character updated :)', 4000);
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            },
            remove: function (character) {
                this.$http.delete('/characters/' + character.id).then(function (resp) {
                    Materialize.toast('Character removed!', 4000);
                    this.characters.$remove(character);
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            }
        },
    });

    var not_vm = new Vue({
        el: '#notification_panel',
        data: {
            use_mobile: {{ \Auth::user()->mobile_phone === null ? 'false' : 'true' }},
            mobile_phone: '{{ \Auth::user()->mobile_phone }}',
            frequency: {{ \Auth::user()->frequency ?? 30 }},
        },
        methods: {
            update: function () {
                this.$http.patch('/users', { phone: this.mobile_phone, frequency: this.frequency }).then(function (resp) {
                    this.use_mobile = true;
                    Materialize.toast('Updated your settings :)', 4000);
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            }
        }
    });
</script>
@endsection
