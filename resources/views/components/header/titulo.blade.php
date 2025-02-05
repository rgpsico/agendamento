<div class="page-header">
    <div class="row">
        <div class="col-10">
            <h3 class="page-title">{{$pageTitle ?? ''}}</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="">Admin</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="javascript:(0);">{{$pageTitle ?? ''}} </a>
                </li>
          
            </ul>
        </div>
    </div>
    @if(isset($btAdd) && $btAdd ==  'true')
    <div class="row">
        <div class="col-2 my-4">
            @if(!isset($modal))
                <button class="btn btn-success Adicionar{{$pageTitle}}">
                    <i class="icon icon-plus">Adicionar {{$pageTitle}}</i>
                </button>
            @else 
                <button  class="btn btn-success" id="Adicionar{{$pageTitle}}">
                    <i class="icon icon-plus">Adicionar {{$pageTitle}}</i>
                </button>
            @endif    
        </div>
    </div>
    @endif
</div>


@if(isset($modal))
<div class="modal fade Adicionar{{$pageTitle}}" id="Adicionar{{$pageTitle}}" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Personal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row form-row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" value="John">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" value="Doe">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" value="24-07-1983">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Email ID</label>
                                <input type="email" class="form-control" value="johndoe@example.com">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" value="+1 202-555-0125" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <h5 class="form-title"><span>Address</span></h5>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                            <label>Address</label>
                                <input type="text" class="form-control" value="4663 Agriculture Lane">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" value="Miami">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" class="form-control" value="Florida">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Zip Code</label>
                                <input type="text" class="form-control" value="22434">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control" value="United States">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif