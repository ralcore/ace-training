<!DOCTYPE html>
<html>
    <head>
        <title>Course View</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/approvalview.css">
    </head>

    <body>
        <div class="container-fluid" style="max-width: 640px;">
            <div class="row rounded" style="margin-top:8px">
                <div class="col-sm-12">
                    <div class="card card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Type</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th class="col">Course</th>
                            <th scope="col">Date</th>
                            <th class="col">Approve</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Tutor</th>
                                <td>real</td>
                                <td>person</td>
                                <td>CSCCORE001</td>
                                <td>2021-04-23</td>
                                <td class="d-flex flex-row-reverse">
                                    <button type="button" class="btn btn-danger"><i class="bi bi-x"></i></button>
                                    <button type="button" class="btn btn-success"><i class="bi bi-check"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Tutor</th>
                                <td>samantha</td>
                                <td>davies</td>
                                <td>HIST002</td>
                                <td>2021-05-23</td>
                                <td class="d-flex flex-row-reverse">
                                    <button type="button" class="btn btn-danger"><i class="bi bi-x"></i></button>
                                    <button type="button" class="btn btn-success"><i class="bi bi-check"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Student</th>
                                <td>person with a real long</td>
                                <td>name just to spite you</td>
                                <td>COURSENAMEHERE</td>
                                <td>2021-07-23</td>
                                <td class="d-flex flex-row-reverse">
                                    <button type="button" class="btn btn-danger"><i class="bi bi-x"></i></button>
                                    <button type="button" class="btn btn-success"><i class="bi bi-check"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                        <a href="#" class="card-link">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>