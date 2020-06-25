@php
    $details = $vendor->pivot->details;
    if(!empty($details))
        $details = json_decode($details);
@endphp

<tr>
     <td>
         <input type="text" name="vendor[{{ $vendor->id }}][amd][]"  disabled class="form-control" placeholder="Currency" value="amd" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) ? $details->amd->price : '' }}"
          name="vendor[{{ $vendor->id }}][amd][price]"   class="form-control" placeholder="Price"  style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->prepayment->min) ? $details->amd->prepayment->min: '' }}"
          name="vendor[{{ $vendor->id }}][amd][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->prepayment->max) ? $details->amd->prepayment->max: '' }}"
          name="vendor[{{ $vendor->id }}][amd][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->percent->min) ? $details->amd->percent->min: '' }}"
          name="vendor[{{ $vendor->id }}][amd][percent][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->percent->max) ? $details->amd->percent->max: '' }}"
          name="vendor[{{ $vendor->id }}][amd][percent][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->deadline->min) ? $details->amd->deadline->min: '' }}"
          name="vendor[{{ $vendor->id }}][amd][deadline][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->deadline->max) ? $details->amd->deadline->max: '' }}"
          name="vendor[{{ $vendor->id }}][amd][deadline][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->fee->min) ? $details->amd->fee->min: '' }}"
          name="vendor[{{ $vendor->id }}][amd][fee][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->fee->max) ? $details->amd->fee->max: '' }}"
          name="vendor[{{ $vendor->id }}][amd][fee][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->insurance->min) ? $details->amd->insurance->min: '' }}"
          name="vendor[{{ $vendor->id }}][amd][insurance][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->amd) && isset($details->amd->insurance->max) ? $details->amd->insurance->max: '' }}"
          name="vendor[{{ $vendor->id }}][amd][insurance][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
</tr>
<tr>
     <td>
         <input type="text" name="vendor[{{ $vendor->id }}][usd][]"  disabled class="form-control" placeholder="Currency" value="usd" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) ? $details->usd->price : '' }}"
        name="vendor[{{ $vendor->id }}][usd][price]"   class="form-control" placeholder="Price"  style="width:100%">

     </td>
     <td>
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->prepayment->min) ? $details->usd->prepayment->min: '' }}"
        name="vendor[{{ $vendor->id }}][usd][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->prepayment->max) ? $details->usd->prepayment->max: '' }}"
        name="vendor[{{ $vendor->id }}][usd][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->percent->min) ? $details->usd->percent->min: '' }}"
        name="vendor[{{ $vendor->id }}][usd][percent][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->percent->max) ? $details->usd->percent->max: '' }}"
        name="vendor[{{ $vendor->id }}][usd][percent][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->deadline->min) ? $details->usd->deadline->min: '' }}"
        name="vendor[{{ $vendor->id }}][usd][deadline][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->deadline->max) ? $details->usd->deadline->max: '' }}"
        name="vendor[{{ $vendor->id }}][usd][deadline][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->fee->min) ? $details->usd->fee->min: '' }}"
        name="vendor[{{ $vendor->id }}][usd][fee][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->fee->max) ? $details->usd->fee->max: '' }}"
        name="vendor[{{ $vendor->id }}][usd][fee][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->insurance->min) ? $details->usd->insurance->min: '' }}"
        name="vendor[{{ $vendor->id }}][usd][insurance][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
        value="{{ isset($details->usd) && isset($details->usd->insurance->max) ? $details->usd->insurance->max: '' }}"
        name="vendor[{{ $vendor->id }}][usd][insurance][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
</tr>
<tr>
     <td>
         <input type="text" name="vendor[{{ $vendor->id }}][eur][]"  disabled class="form-control" placeholder="Currency" value="eur" style="width:100%">
         <input type="text"
         value="{{ isset($details->eur) ? $details->eur->price : '' }}"
         name="vendor[{{ $vendor->id }}][eur][price]"   class="form-control" placeholder="Price"  style="width:100%">

     </td>
     <td>
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->prepayment->min) ? $details->eur->prepayment->min: '' }}"
            name="vendor[{{ $vendor->id }}][eur][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->prepayment->max) ? $details->eur->prepayment->max: '' }}"
            name="vendor[{{ $vendor->id }}][eur][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->percent->min) ? $details->eur->percent->min: '' }}"
            name="vendor[{{ $vendor->id }}][eur][percent][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->percent->max) ? $details->eur->percent->max: '' }}"
            name="vendor[{{ $vendor->id }}][eur][percent][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->deadline->min) ? $details->eur->deadline->min: '' }}"
            name="vendor[{{ $vendor->id }}][eur][deadline][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->deadline->max) ? $details->eur->deadline->max: '' }}"
            name="vendor[{{ $vendor->id }}][eur][deadline][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->fee->min) ? $details->eur->fee->min: '' }}"
            name="vendor[{{ $vendor->id }}][eur][fee][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->fee->max) ? $details->eur->fee->max: '' }}"
            name="vendor[{{ $vendor->id }}][eur][fee][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->insurance->min) ? $details->eur->insurance->min: '' }}"
            name="vendor[{{ $vendor->id }}][eur][insurance][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->eur) && isset($details->eur->insurance->max) ? $details->eur->insurance->max: '' }}"
            name="vendor[{{ $vendor->id }}][eur][insurance][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
</tr>
<tr>
     <td>
         <input type="text" name="vendor[{{ $vendor->id }}][rub][]"  disabled class="form-control" placeholder="Currency" value="rub" style="width:100%">
         <input type="text"
         value="{{ isset($details->rub) ? $details->rub->price : '' }}"
         name="vendor[{{ $vendor->id }}][rub][price]"   class="form-control" placeholder="Price"  style="width:100%">

     </td>
     <td>
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->prepayment->min) ? $details->rub->prepayment->min: '' }}"
            name="vendor[{{ $vendor->id }}][rub][prepayment][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->prepayment->max) ? $details->rub->prepayment->max: '' }}"
            name="vendor[{{ $vendor->id }}][rub][prepayment][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->percent->min) ? $details->rub->percent->min: '' }}"
            name="vendor[{{ $vendor->id }}][rub][percent][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->percent->max) ? $details->rub->percent->max: '' }}"
            name="vendor[{{ $vendor->id }}][rub][percent][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->deadline->min) ? $details->rub->deadline->min: '' }}"
            name="vendor[{{ $vendor->id }}][rub][deadline][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->deadline->max) ? $details->rub->deadline->max: '' }}"
            name="vendor[{{ $vendor->id }}][rub][deadline][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->fee->min) ? $details->rub->fee->min: '' }}"
            name="vendor[{{ $vendor->id }}][rub][fee][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->fee->max) ? $details->rub->fee->max: '' }}"
            name="vendor[{{ $vendor->id }}][rub][fee][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
     <td>
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->insurance->min) ? $details->rub->insurance->min: '' }}"
            name="vendor[{{ $vendor->id }}][rub][insurance][min]"  class="form-control" placeholder="min" style="width:100%">
         <input type="text"
            value="{{ isset($details->rub) && isset($details->rub->insurance->max) ? $details->rub->insurance->max: '' }}"
            name="vendor[{{ $vendor->id }}][rub][insurance][max]"  class="form-control" placeholder="max" style="width:100%">
     </td>
</tr>
