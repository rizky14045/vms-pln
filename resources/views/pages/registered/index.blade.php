@extends('layout.app')
@section('content')
<div class="p-6">
   <h2 class="text-xl font-semibold mb-4">Daftar Register Employee</h2>
      <div class="grid grid-cols-12">
         <div class="col-span-12">
            <div class="card border-0 overflow-hidden">
               <div class="card-body">
                  <table id="selection-table" class="border border-neutral-200 dark:border-neutral-600 rounded-lg border-separate	">
                     <thead>
                        <tr>
                           <th scope="col" class="text-neutral-800 dark:text-white">
                              <div class="form-check style-check flex items-center">
                                 <label class="ms-2 form-check-label" for="serial">
                                 No
                                 </label>
                              </div>
                           </th>
                           <th scope="col" class="text-neutral-800 dark:text-white">
                              <div class="flex items-center gap-2">
                                 NID
                              </div>
                           </th>
                           <th scope="col" class="text-neutral-800 dark:text-white">
                              <div class="flex items-center gap-2">
                                 Name
                              </div>
                           </th>
                           <th scope="col" class="text-neutral-800 dark:text-white">
                              <div class="flex items-center gap-2">
                                 Status
                              </div>
                           </th>
                           <th scope="col" class="text-neutral-800 dark:text-white">
                              <div class="flex items-center gap-2">
                                 Action
                              </div>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($registeredPersons as $people)   
                            <tr>
                                <td>
                                    <div class="form-check style-check flex items-center">
                                        <label class="ms-2 form-check-label">
                                        {{$loop->iteration}}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <h6 class="text-base mb-0 font-medium grow">{{$people->user->nid ?? ''}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-base mb-0 font-medium grow">{{$people->user->name ?? ''}}</h6>
                                </td>
                                <td>
                                    @if ($people->status_level == 1)
                                        <span class="bg-warning-100 dark:bg-warning-600/25 text-warning-600 dark:text-warning-400 px-6 py-1.5 rounded-full font-medium text-sm">            
                                            {{$people->status}}
                                        </span>
                                    @else 
                                        <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">
                                            {{$people->status}}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('registered.show',['id'=>$people->id])}}" class="w-8 h-8 bg-primary-50 dark:bg-primary-600/10 text-primary-600 dark:text-primary-400 rounded-full inline-flex items-center justify-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{route('registered.approve',['id'=>$people->id])}}" class="w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center">
                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
</div>
@endsection
@section('scripts')
<script>
   if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {
   
     let multiSelect = true;
     let rowNavigation = false;
     let table = null;
   
     const resetTable = function() {
         if (table) {
             table.destroy();
         }
   
         const options = {
           columns: [
             { select: [0, 4], sortable: false } // Disable sorting on the first column (index 0 and 6)
           ],
             rowRender: (row, tr, _index) => {
                 if (!tr.attributes) {
                     tr.attributes = {};
                 }
                 if (!tr.attributes.class) {
                     tr.attributes.class = "";
                 }
                 if (row.selected) {
                     tr.attributes.class += " selected";
                 } else {
                     tr.attributes.class = tr.attributes.class.replace(" selected", "");
                 }
                 return tr;
             }
         };
         if (rowNavigation) {
             options.rowNavigation = true;
             options.tabIndex = 1;
         }
   
         table = new simpleDatatables.DataTable("#selection-table", options);
   
         // Mark all rows as unselected
         table.data.data.forEach(data => {
             data.selected = false;
         });
   
         table.on("datatable.selectrow", (rowIndex, event) => {
             event.preventDefault();
             const row = table.data.data[rowIndex];
             if (row.selected) {
                 row.selected = false;
             } else {
                 if (!multiSelect) {
                     table.data.data.forEach(data => {
                         data.selected = false;
                     });
                 }
                 row.selected = true;
             }
             table.update();
         });
     };
   
     // Row navigation makes no sense on mobile, so we deactivate it and hide the checkbox.
     const isMobile = window.matchMedia("(any-pointer:coarse)").matches;
     if (isMobile) {
         rowNavigation = false;
     }
   
     resetTable();
   }
</script>
@endsection