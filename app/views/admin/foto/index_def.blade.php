@extends('admin.layout')

@section('content')
    <script src="<?php echo AppHelper::BaseUrl(); ?>public/js/jquery.sortable.js"></script>
    <div class="col_12">
        <ol class="breadcrumb">
            <li><a href="<?php echo AppHelper::UrlTo('/foto?ref_id='.$data['ref_id'].'&ref='.$data['ref'])?>">Zdjęcia dla obiektu {{ $ref_obj }}</a></li>
            <li class="active">Lista</li>
        </ol>
        <h1>Zdjęcia dla obiektu {{ $ref_obj }} <a href="<?php echo AppHelper::UrlTo('/'.$data['ref'].'/edit?id='.$data['ref_id'])?>" class="btn btn-success warning_11">Wróć</a></h1>
        <div class="editor">
            <?php echo Form::open(AppHelper::UrlTo('/foto?from_form=1&ref_id='.$data['ref_id'].'&ref='.$data['ref'].'&id='.$object_foto->id), array('files' => true)) ?>
            <div class="col-md-2">
                <label for="opis" class="control-label">Opis zdjęcia:</label>
                <?php echo Form::error('opis', $errors) ?>
                <?php echo Form::text('opis', Form::FieldValue('opis', $object_foto, $data)) ?>
            </div>
            <div class="col-md-2">
                <label for="plik" class="control-label">Plik:</label>
                @if($object_foto->plik)
                    <div class="clearfix"></div>
                    <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/mini_<?php echo $object_foto->plik; ?>" alt=""/>
                    <br/><input type="checkbox" value="1" name="usun_plik"/> usuń ten plik
                @else
                    <?php echo Form::error('plik', $errors) ?>
                    @if($object_foto->id > 0)
                        <?php echo Form::file('plik') ?>
                    @else
                        <?php echo Form::file('plik[]', array('multiple' => 'multiple')) ?>
                    @endif
                @endif
            </div>
            <div class="col-md-1">
                <label for="kolejnosc" class="control-label">Kolejność:</label>
                <?php echo Form::error('kolejnosc', $errors) ?>
                <?php echo Form::text('kolejnosc', ($object_foto->id > 0)? $object_foto->kolejnosc : $lista["total"] + 1) ?>
            </div>
            <div class="col-md-2">
                <label for="linie" class="control-label">&nbsp;</label><br/>
                <?php echo Form::submit(($object_foto->id > 0)? "Edytuj" : "Dodaj") ?>
            </div>
            <?php echo Form::close() ?>
            <div class="clearfix"></div>
        </div>
        <div class="editor">

            <p class="padding-b-5">Ilość znalezionych wpisów: <b>{{ $lista["total"] }}</b></p>

            @if(count($lista["wyniki"]) > 0)
            <div class="table-responsive">
                <form method="post" action="#" id="action_form">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width: 30px">
                            <input type="checkbox" name="" value="1" id="checked_box" class="check_uncheck"/>
                        </th>
                        <th>Opis</th>
                        <th>Mini</th>
                        <th>Data dodania</th>
                        <th>Kolejność</th>
                        <th class="col-md-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lista["wyniki"] as $rec)
                    <tr>
                        <th scope="row" style="text-align: center">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" name="obj[]" value="{{ $rec->id }}" class="ptaszki"/>
                                    {{ $rec->id }}
                                </label>
                            </div>
                        </th>
                        <td><i>{{ $rec->opis }}</i></td>
                        <td>
                            @if($rec->plik)
                                <img src="<?php echo AppHelper::BaseUrl(); ?>public/uploads/galeria/mini_<?php echo $rec->plik; ?>" alt=""/>
                            @endif
                        </td>
                        <td>{{ date('d.m.Y H:i', strtotime($rec->created_at)) }}</td>
                        <td>{{ $rec->kolejnosc }}</td>
                        <td>
                            <a href="<?php echo AppHelper::UrlTo('/foto/delete_many?obj[]='.$rec->id.'&ref_id='.$data['ref_id'].'&ref='.$data['ref'])?>" type="button" class="btn btn-xs btn-success" onclick="return confirm('Na pewno?')">Usuń</a>
                            <a href="<?php echo AppHelper::UrlTo('/foto?id='.$rec->id.'&ref_id='.$data['ref_id'].'&ref='.$data['ref'])?>" type="button" class="btn btn-xs warning_33 btn-warning">Edytuj</a>
                            <a class="btn btn-info warning_11" href="<?php echo AppHelper::UrlTo('/foto?ref=foto&ref_id='.$rec->id)?>">
                                <i class="lnr lnr-picture"></i><span>&nbsp;Zdjęcia</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7">
                            <div class="btn-group">
                                <button class="btn btn-mini">Akcja</button>
                                <button class="btn dropdown-toggle btn-mini" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="check_uncheck">zaznacz/odznacz wszystkie</a></li>
                                    <li><a href="#" onclick="sendForm('#action_form', {{ "'".AppHelper::UrlTo('/foto/delete_many?ref_id='.$data['ref_id'].'&ref='.$data['ref'])."'"
                                            }}); return false;">usuń zaznaczone</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <input type="hidden" value="<?php echo $req->getAttribute('csrf_name')?>" name="csrf_name" />
                <input type="hidden" value="<?php echo $req->getAttribute('csrf_value')?>" name="csrf_value" />
                </form>
            </div>
            <?php echo $lista["pagination"] ?>
            @endif

        </div>
        <div class="clearfix"></div>
    </div>


@stop
