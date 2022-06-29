@section('title', 'Case Studies')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<div class="container-fluid mt--7">
    <div class="row">

        @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif


        <div class="col-xl-12 mb-5 mb-xl-0">
            <div class="" style="margin-bottom:6%">
                <div class=" bg-transparent">
                    <div class="row align-items-center">
                        <div class="col mx-3">
                            <h1>Case Studies
                                @if(Illuminate\Support\Facades\Auth::user()->role=="Admin")
                                <a href="{{route('casestudy.create')}}" class="btn btn-success1 float-right">Add Case Study

                                </a>
                                @endif
                            </h1>
                           
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <table id="table_id" class="table table-bordered table-responsive-lg">
                            <thead>
                                <tr>
                                    <th>Client name</th>
                                    <th>Case Study</th>
                                    <th>Description</th>
                                    <th>Logo</th>
                                    <th>Action</th>


                                </tr>
                            </thead>


                            @foreach($Casestudy as $data)
                            <tr>
                                <td>{{ $data->Clientname}}</td>
                                <td>{{ $data->caseStudyName}}</td>
                                <td>{!! $data->description !!}</td>
                                <td>
                                    @if($data->Client_company_logo)
                                    <img width="100" height="100" src="{{ asset('ClientLogo/'. $data->Client_company_logo) }}" alt="" border="0" />
                                    @endif
                                </td>
                                <th>
                                    <a href="{{url('/dashboard/CaseStudy/edit/'.$data->id)}}" class="link_color"><i class="fas fa-edit"></i></a>
                                    <a onclick="return confirm('Are you sure?')" href="{{url('/dashboard/CaseStudy/delete/'.$data->id)}}" class="link_color"><i class="far fa-trash-alt"></i></a>
                                </th>
                            </tr>

                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


<script>
    $(document).ready(function() {
        $('#table_id').DataTable();
    });
</script>

@endpush