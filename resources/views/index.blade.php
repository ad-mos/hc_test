<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CSS Compressor</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>

        </style>
    </head>
    <body>
        <h1>CSS Compressor</h1>
        <form action="#" id="compress_form">
            <div class="setting-item">
                <label for="compression_level">Compression level:</label>
                <select id="compression_level" name="compression_level">
                    @foreach(\App\Services\Compressor::getCompressionLevels() as $level => $name)
                        <option value="{{ $level }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="setting-item">
                <label for="sort_mode">Sort properties:</label>
                <select id="sort_mode" name="sort_mode">
                    @foreach(\App\Services\Compressor::getSortModes() as $mode => $name)
                        <option value="{{ $mode }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="setting-item">
                <label for="compress_colors">Compress colors:</label>
                <input type="checkbox" id="compress_colors" name="compress_colors" checked>
            </div>
            <div class="setting-item">
                <label for="compress_font_weight">Compress font-weight:</label>
                <input type="checkbox" id="compress_font_weight" name="compress_font_weight" checked>
            </div>
            <div class="setting-item">
                <label for="remove_backslashes">Remove unnecessary backslashes:</label>
                <input type="checkbox" id="remove_backslashes" name="remove_backslashes" checked>
            </div>
            <div class="setting-item">
                <label for="remove_semicolons">Remove last semi-colons:</label>
                <input type="checkbox" id="remove_semicolons" name="remove_semicolons" checked>
            </div>
            <div class="data-block">
                <h3>Input:</h3>
                <textarea name="css_source" id="css_source" cols="50" rows="10"></textarea>
            </div>
            <input type="submit" id="compress_button" value="Compress">
        </form>
        <div class="data-block">
            <h3>Output:</h3>
            <textarea name="css_compressed" id="css_compressed" cols="50" rows="10" readonly></textarea>
        </div>
    </body>
    <script>
        $(function() {
            $('#compress_button').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('compress.submit') }}',
                    data: $("#compress_form").serialize(),
                    success: function (res) {
                        $('#css_compressed').text(res.result);
                    },
                    error: function (res) {
                        alert(res.responseJSON.message);
                    }
                });
            })
        });
    </script>
</html>
