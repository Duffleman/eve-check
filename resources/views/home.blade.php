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
                            <th>Status</th>
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
                            <td v-show="character.status === 'offline'"><span class="red-text">OFFLINE</span></td>
                            <td v-show="character.status === 'online'"><span class="green-text">ONLINE</span></td>
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
                <div class="row">
                    <form class="col s12" v-on:submit.prevent="update">
                        <div class="row">
                            <div class="input-field col s12">
                                <select name="frequency" id="frequency" class="validate" v-model="frequency">
                                    <option value="-1">Do nothing</option>
                                    <option value="1">Once</option>
                                    <option value="10">Every 10 minutes</option>
                                    <option value="30">Every 30 minutes</option>
                                    <option value="60">Every hour</option>
                                </select>
                                <label for="frequency">If a character is offline, alert me:</label>
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
    <div class="col s6">
        <div class="card">
            <div class="card-content">
                <div class="alert red darken-1" v-show="notifiers.length == 0">
                    <h4>No methods set</h4>
                    <p>You have no methods of communication set, therefore we cannot message you if your character is found offline :/</p>
                </div>
                <a href="#" v-on:click="showNotificationCreatePanel = !showNotificationCreatePanel" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                <span class="card-title">Notification Methods</span>
                <div class="row" v-show="showNotificationCreatePanel">
                    <form v-on:submit.prevent="postNewNotifier" class="col s12">
                        <div class="row">
                            <div class="input-field col s6">
                                <select name="type" id="type" class="validate" v-model="type">
                                    <option value="email">Email</option>
                                    <option value="mobile">Mobile Phone (SMS)</option>
                                </select>
                                <label for="type">How should we contact you?</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="value" type="text" class="validate" v-model="value">
                                <label for="value">Value (Mobile or Email)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <button class="btn waves-effect waves-light red" type="submit" name="action">Add
                                <i class="material-icons right">add</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col s12">
                        <ul class="collection">
                            <li class="collection-item" v-for="notifier in notifiers"><a href="#" @click="delete(notifier)"><i class="material-icons red-text right">delete</i></a>&nbsp;@{{ notifier.label }}: @{{ notifier.value }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>

    $(document).ready(function() {
        $('select').material_select();
    });

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
            showNotificationCreatePanel: false,
            typeLabel: 'Email',
            frequency: {{ \Auth::user()->frequency }},
            notifiers: [],
        },
        ready: function() {
            this.$http.get('/notifiers').then(function (resp) {
                this.$set('notifiers', resp.data);
            });
        },
        methods: {
            updateLabel: function() {
                this.typeLabel = "SMS";
            },
            postNewNotifier: function() {
                var type = $('#type').val();
                if (!type) {
                    type = 'email';
                }
                var value = this.value;
                this.$http.post('/notifiers', { type, value }).then(function (resp) {
                    Materialize.toast('Notifier added!', 4000);
                    this.showNotificationCreatePanel = false;
                    var label = capitalizeFirstLetter(type);
                    this.notifiers.push({ label, type, value });
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            },
            delete: function(notifier) {
                this.$http.delete('/notifiers', { notifier }).then(function (resp) {
                    this.notifiers.$remove(notifier);
                    Materialize.toast('Removed ^^', 4000);
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            },
            update: function() {
                var frequency = $('#frequency').val();
                this.$http.patch('/users', { frequency }).then(function (resp) {
                    Materialize.toast('Notification settings updated :)', 4000);
                }).catch(function (resp) {
                    Materialize.toast('Something went wrong :(', 4000);
                });
            },
        },
    });

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>
@endsection
