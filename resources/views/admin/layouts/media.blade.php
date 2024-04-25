
<div class="overlay-back" style="display: none"></div>
<div class="choose-panel" style="display: none">
    <div class="row m-0">
        <div class="col-sm-9 p-0">
            <div class="card-header">
                <a href="javascript:void(0)" class="btn btn-danger btn-xs float-right" style="opacity: 0"><i class="far fa-times-circle"></i></a>
            </div>
            <div class="infinite-scroll" style="overflow:auto; height:80vh">
                <div class="scroll-content allmedia" style="display: block">
                @foreach ($medias as $media)
                @php
                    $id = $media->id;
                    $media = $media->getMedia()->first();
                @endphp
                @if ($id!=1)
                    <div class="thumbnail">
                        <a href="javascript:void(0);">
                            <img id="{{ $id }}" src="{{ $media->getUrl('thumb') }}" data-url="{{ $media->getUrl() }}" alt="{{$media->alt}}" data-title="{{$media->name}}">
                        </a>
                    </div>
                @endif
                @endforeach
                {{ $medias->links() }}
                </div>
                <div class="add-medias" style="display: none">
                    <form id="media-form" action="{{route('admin.media.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="media_title" value="media_title" hidden>
                        <div class="card-body"> 
                          <div id="error-container"></div> <br>
                            <div class="needsclick dropzone" id="document-dropzone">
                            </div>
                            <div class="mt-4">
                            <a href="{{ route('admin.media.index') }}" class="btn btn-danger">Cancel</a>
                            <button class="btn btn-success text-center px-5" type="submit" id="submitmedia">{{ __('main.Upload') }}</button>
                            <label class="ml-5" style="color: red"><b>Maximum file size: 2MB</b></label>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-3 p-0" style="background-color: whitesmoke;">
            <div class="card-header">
                <a href="javascript:void(0)" id="add_media" class="btn btn-danger btn-xs float-left">{{__('main.Mediaadd')}}</a>

                <a href="javascript:void(0)" class="btn btn-success btn-xs float-left ml-2" id="allmedia">{{ __('main.All Media') }}</a>

                <a id="close" href="javascript:void(0)" class="btn btn-danger btn-xs float-right"><i class="far fa-times-circle"></i></a>
            </div>
            <div class="card-body">
                <div style="overflow: auto">
                <img src="" alt="" id="onizleme">
                <form action="" method="post" id="form_img">
                    @method('PUT')
                    @csrf
                    <input type="hidden" id="value">
                    <input type="hidden" value="" id="media" name="media">
                    <input type="hidden" value="form_img" name="form_img">
                    <div class="form-group">
                        <label for="media_title">{{ __('main.Name') }}</label>
                        <input type="text" class="form-control form-control-sm" value="" id="media_title" name="media_title">
                    </div>
                    <div class="form-group">
                        <label for="media_alt">{{ __('main.Alt Tag') }}</label>
                        <input type="text" class="form-control form-control-sm" value="" id="media_alt" name="media_alt">
                    </div>
                    <div class="form-group">
                        <label for="media_url">{{ __('main.Url') }}</label>
                        <input type="text" class="form-control form-control-sm" value="" id="media_url" name="media_url" disabled>
                    </div>
                </form>
                <a href="javascript:void(0);" class="btn btn-primary btn-xs" style="bottom: 0;" id="submit_img">{{ __('main.Update') }}</a>
                <a href="javascript:void(0);" class="btn btn-success btn-xs float-right" style="bottom: 0;" id="add">{{ __('main.Choose Image') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

