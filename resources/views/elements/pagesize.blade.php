@php
    $pagesize = session('pagesize');
    if(!$pagesize){$pagesize = 15;}
@endphp 
<div class="float-left">      
    <form class="form-inline" action="{{route('set_pagesize')}}" method="post" id="pagesize_form">
        @csrf
        <label for="pagesize" class="control-label mt-2">{{__('page.show')}} :</label>
        <select class="form-control form-control-sm mx-md-2 mt-2" name="pagesize" id="pagesize">
            <option value="15" @if($pagesize == '15') selected @endif>15</option>
            <option value="50" @if($pagesize == '50') selected @endif>50</option>
            <option value="100" @if($pagesize == '100') selected @endif>100</option>
            <option value="200" @if($pagesize == '200') selected @endif>200</option>
            <option value="500" @if($pagesize == '500') selected @endif>500</option>
            <option value="100000" @if($pagesize == '100000') selected @endif>All</option>
        </select>
    </form>
</div>  