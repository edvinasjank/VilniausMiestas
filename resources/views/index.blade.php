@extends('base')

@section('title', 'Page Title')


@section('content')

        <div class="row">
            <div class="col-lg-12" id="alerts">
                @if(session()->has('success_message'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('success_message') }}
                    </div>
                @endif
                @if(session()->has('error_message'))
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ session()->get('error_message') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h1>Vilniaus miesto žmonės:</h1>
            </div>
            <div class="col-lg-12" >
                <form action="/import.html" method="post" enctype="multipart/form-data">
                    <div class="text-right">
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#importModal">Importuoti duomenis</button>
                    </div>
                    <!-- Import modal -->
                    <div class="modal fade import_modal" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Duomenų importavimas</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="file" name="file">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
                                        <button type='submit' class="btn btn-primary">Importuoti</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

            <form method="get">
                <div class="row">
                    <div class="col-lg-3">
                        <label>Adresas</label>
                        <input type="text" class="form-control" id="adress" name="adress" value="{{$address}}">
                     </div>
                    <div class="col-lg-3">
                        <label>Lytis</label>
                        <select name="genderOption" id="genderOption" class="form-control">
                            <option value="" ></option>
                            <option value="V" <?php if(isset($_GET['genderOption']) && $_GET['genderOption'] == "V") {echo 'selected'; }?>>Vyras</option>
                            <option value="M" <?php if(isset($_GET['genderOption']) && $_GET['genderOption'] == "M") {echo 'selected'; }?>>Moteris</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Vaikai</label>
                        <select name="hasKidsOption" id="hasKidsOption" class="form-control">
                            <option value=""></option>
                            <option value="hasKids" <?php if(isset($_GET['hasKidsOption']) && $_GET['hasKidsOption'] == "hasKids") {echo 'selected'; }?>>Turi vaikų</option>
                            <option value="hasntKids" <?php if(isset($_GET['hasKidsOption']) && $_GET['hasKidsOption'] == "hasntKids") {echo 'selected'; }?>>Neturi vaikų</option>
                        </select>
                    </div>
                    <div class="space-25 col-lg-3">
                        <input class="btn btn-primary " type="submit" value="Ieškoti">
                    </div>
                </div>
                <div class="row space-15">


                </div>
                <div class="col-lg-12 text-right">
                    <label>Įrašų kiekis: </label>
                    <label>
                        <select name="limit" onchange="this.form.submit()">
                            <option value="10" <?php if(isset($_GET['limit']) && $_GET['limit'] == "10") {echo 'selected'; }?>>10</option>
                            <option value="20" <?php if(isset($_GET['limit']) && $_GET['limit'] == "20") {echo 'selected'; }?>>20</option>
                            <option value="30" <?php if(isset($_GET['limit']) && $_GET['limit'] == "30") {echo 'selected'; }?>>30</option>
                            <option value="40" <?php if(isset($_GET['limit']) && $_GET['limit'] == "40") {echo 'selected'; }?>>40</option>
                            <option value="50" <?php if(isset($_GET['limit']) && $_GET['limit'] == "50") {echo 'selected'; }?>>50</option>
                        </select>
                    </label>
                </div>
                <div class="col-lg-12 text-right">
                    <label>Rikiuoti pagal gatvę: </label>
                    <label>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="asc" <?php if(isset($_GET['sort']) && $_GET['sort'] == "asc") {echo 'selected'; }?>>A-Ž</option>
                            <option value="desc" <?php if(isset($_GET['sort']) && $_GET['sort'] == "desc") {echo 'selected'; }?>>Ž-A</option>
                        </select>
                    </label>
                </div>
                <div class="row space-30">
                    <div class="col-lg-12 text-right">
                        <button type="submit" name="download" class="btn-link">Atsisiųsti</button>
                    </div>
                </div>
            </form>

        <div class="row">
            <div class="col-lg-12">
                <form action="/delete-people.html" method="post" onsubmit="return confirm('Ištrinti pasirinktus įrašus?');">
                    <label>
                        <input type="submit" class="btn btn-danger " name="submit" value="Trinti" >
                    </label>

                    <table class="table" >
                        <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>GIMIMO METAI</th>
                            <th>GIMIMO VALSTYBĖ</th>
                            <th>LYTIS</th>
                            <th>ŠEIMOS PADĖTIS</th>
                            <th>KIEK TURI VAIKŲ</th>
                            <th>SENIUNIJA</th>
                            <th>GATVĖ</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($persons as $person)
                                <tr>
                                    <td><input type="checkbox" class="checkBoxes" name="people[]" id="id" value="{{$person->id}}"></td>
                                    <th scope="row">
                                        {{$i++}}
                                    </th>
                                    <td>{{$person->birth_year}}</td>
                                    <td>{{$person->birth_country}}</td>
                                    <td>{{$person->gender}}</td>
                                    <td>{{$person->family_situation}}</td>
                                    <td>{{$person->kids}}</td>
                                    <td>{{$person->location}}</td>
                                    <td>{{$person->street}}</td>
                                    <td>
                                        <button  type="button" data-id="{{$person->id}}" class="btn btn-default btn-sm" data-toggle="modal" data-target="#exampleModal{{$person->id}}">
                                                <span class="glyphicon glyphicon-edit" title="Redaguoti"></span>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade edit_modal" id="exampleModal{{$person->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="exampleModalLabel">Įrašo redagavimas</h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                            <input type="hidden" class="id" value="{{$person->id}}">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-control-label">Gatvė:
                                                                        <input type="text" class="form-control street" value="{{$person->street}}">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-control-label">Kiek turi vaikų:
                                                                        <input class="form-control kids" type="number" min="0" step="1" value="{{$person->kids}}"/>
                                                                    </label>
                                                                </div>
                                                                <input type="file" name="file">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
                                                                <button type="button" id="save" value="{{$person->id}}" class="btn btn-primary">Išsaugoti</button>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-lg-12">
                {{ $persons->appends(request()->input())->links() }}
            </div>
        </div>
@endsection