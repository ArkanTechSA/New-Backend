<x-layout.default :title="'Change Password'">

    <div class="py-4 container-xxl">
        <div class="row justify-content-center">
            <div class="col-md-12">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                        <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-md-12">
                  
                  <!-- Change Password -->
                  <div class="mb-12 card">
                    <h5 class="card-header">Change Password</h5>
                    <div class="pt-1 card-body">
                   <form method="POST" action="{{ route('admin.change-password') }}">
    @csrf
                        <div class="mb-2 row mb-sm-6">
                          <div class="col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                name="current_password"
                                id="currentPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                              <span class="cursor-pointer input-group-text"
                                ><i class="icon-base ti tabler-eye-off icon-xs"></i
                              ></span>
                            </div>
                          </div>
                        </div>
                        <div class="mb-2 row gy-sm-6 gy-2 mb-sm-0">
                          <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                id="newPassword"
                                name="new_password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                              <span class="cursor-pointer input-group-text"
                                ><i class="icon-base ti tabler-eye-off icon-xs"></i
                              ></span>
                            </div>
                          </div>

                          <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                name="new_password_confirmation"
                                id="confirmPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                              <span class="cursor-pointer input-group-text"
                                ><i class="icon-base ti tabler-eye-off icon-xs"></i
                              ></span>
                            </div>
                          </div>
                        </div>
                        <h6 class="text-body">Password Requirements:</h6>
                        <ul class="mb-0 ps-4">
                          <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                          <li class="mb-4">At least one lowercase character</li>
                          <li>At least one number, symbol, or whitespace character</li>
                        </ul>
                        <div class="mt-6">
                          <button type="submit" class="btn btn-primary me-3">Save changes</button>
                          <button type="reset" class="btn btn-label-secondary">Reset</button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <!--/ Change Password -->


                 
                </div>
              </div>
            </div>
            <!-- / Content -->

           
            <div class="content-backdrop fade"></div>
          </div>

            </div>
        </div>
    </div>

</x-layout.default>

