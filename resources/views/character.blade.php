<div class="container-fluid">
    <h1 class="mb-4">Информация о персонаже</h1>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Имя персонажа</th>
                    <th>Статус</th>
                    <th>Вид</th>
                    <th>Пол</th>
                    <th>Название локации</th>
                    <th>URL локации</th>
                    <th>Эпизоды</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $character->name }}</td>
                    <td>{{ $character->status }}</td>
                    <td>{{ $character->species }}</td>
                    <td>{{ $character->gender }}</td>
                    <td>{{ $character->location->name ?? 'Неизвестно' }}</td>
                    <td>{{ $character->location->url ?? 'Неизвестно' }}</td>
                    <td>
                        <div style="max-height: 150px; overflow-y: auto;">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Название эпизода</th>
                                    <th>Дата выхода</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($character->episodes as $episode)
                                    <tr>
                                        <td>{{ $episode->name }}</td>
                                        <td>{{ $episode->air_date }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Поле ввода для ID персонажа -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="input-group">
                <input type="number" id="character-id" class="form-control" placeholder="Введите ID персонажа" min="1" max="{{ $maxId }}">
                <button id="change-character" class="btn btn-primary" style="margin-right: 20px"  onclick="changeCharacter()">Перейти</button>
                <button id="export-exel" class="btn btn-primary" onclick="exportCharacterToExel()">Экспорт в Exel</button>
            </div>
        </div>
    </div>
</div>
