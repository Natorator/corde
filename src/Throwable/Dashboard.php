<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->throwable['type']; ?></title>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='robots' content='no-index, no-follow'>
</head>
<body>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Message</th>
            <th>File</th>
            <th>Line</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class=""><span class=""><?php echo $this->throwable['code']; ?></span></td>
            <td class=""><span class=""><?php echo $this->throwable['type']; ?></span></td>
            <td class=""><span class=""><?php echo $this->throwable['message']; ?></span></td>
            <td class=""><span class=""><?php echo $this->throwable['file']; ?></span></td>
            <td class=""><span class=""><?php echo $this->throwable['line']; ?></span></td>
        </tr>
        </tbody>
    </table>
</body>
</html>