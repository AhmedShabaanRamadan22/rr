<div class="col-xl-12">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">{{trans("translation.Statistics Based on Operation Type")}}</h4>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <ul class="nav nav-pills gap-2 flex-grow-1 order-2 order-lg-1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#orders-tab" role="tab"
                            aria-selected="true">
                            الطلبات
                        </a>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#facilities-tab" role="tab"
                            aria-selected="false" tabindex="-1">
                            المنشآت
                        </a>
                    </li> -->
                    <!-- <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#education" role="tab" aria-selected="false"
                            tabindex="-1">
                            الوجبات
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab"
                            aria-selected="false" tabindex="-1">
                            البلاغات
                        </a>
                    </li>


                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#securityPrivacy" role="tab"
                            aria-selected="false" tabindex="-1">
                            الاسناد
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#securityPrivacy" role="tab"
                            aria-selected="false" tabindex="-1">
                            الاستمارات
                        </a>
                    </li> -->
                </ul>

            </div>
            <div class="">

                <div class="tab-content">
                    <div class="tab-pane active" id="orders-tab" role="tabpanel">

                        <div class="card-body">
                            <div class="row">
                                @include('admin.dashboard.temp-admin.sections.order-pie-chart')
                                @include('admin.dashboard.temp-admin.sections.order-column-stacked')
                                @include('admin.dashboard.temp-admin.sections.order-table')
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane " id="facilities-tab" role="tabpanel">

                        <div class="card-body">
                            <div class="row">
                                @include('admin.dashboard.temp-admin.sections.facility-charts')
                                @include('admin.dashboard.temp-admin.sections.facility-column-stacked')
                                @include('admin.dashboard.temp-admin.sections.facility-table')
                            </div>
                        </div>

                    </div>
                    --}}
                    <!--end tab-pane-->
                    <div class="tab-pane" id="changePassword" role="tabpanel">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Changes Password</h6>
                        </div>
                        <div class="card-body">
                            <form action="pages-profile-settings">
                                <div class="row g-2 justify-content-lg-between align-items-center">
                                    <div class="col-lg-4">
                                        <div class="auth-pass-inputgroup">
                                            <label for="oldpasswordInput" class="form-label">Old Password*</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control password-input"
                                                    id="oldpasswordInput" placeholder="Enter current password">
                                                <button
                                                    class="btn btn-link position-absolute top-0 end-0 text-decoration-none text-muted password-addon"
                                                    type="button"><i class="ri-eye-fill align-middle"></i></button>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="auth-pass-inputgroup">
                                            <label for="password-input" class="form-label">New Password*</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control password-input"
                                                    id="password-input" onpaste="return false"
                                                    placeholder="Enter new password" aria-describedby="passwordInput"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="auth-pass-inputgroup">
                                            <label for="confirm-password-input" class="form-label">Confirm
                                                Password*</label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control password-input"
                                                    onpaste="return false" id="confirm-password-input"
                                                    placeholder="Confirm password"
                                                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="javascript:void(0);"
                                            class="link-primary text-decoration-underline">Forgot
                                            Password ?</a>
                                        <div class="">

                                            <button type="submit" class="btn btn-success">Change Password</button>
                                        </div>
                                    </div>

                                    <!--end col-->

                                    <div class="col-lg-12">
                                        <div class="card bg-light shadow-none passwd-bg" id="password-contain">
                                            <div class="card-body">
                                                <div class="mb-4">
                                                    <h5 class="fs-sm">Password must contain:</h5>
                                                </div>
                                                <div class="">
                                                    <p id="pass-length" class="invalid fs-xs mb-2">Minimum <b>8
                                                            characters</b></p>
                                                    <p id="pass-lower" class="invalid fs-xs mb-2">At <b>lowercase</b>
                                                        letter (a-z)
                                                    </p>
                                                    <p id="pass-upper" class="invalid fs-xs mb-2">At least
                                                        <b>uppercase</b> letter
                                                        (A-Z)
                                                    </p>
                                                    <p id="pass-number" class="invalid fs-xs mb-0">A least
                                                        <b>number</b> (0-9)
                                                    </p>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                            <div
                                class="mt-4 mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="card-title  mb-0">Login History</h5>
                                <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-secondary">All Logout</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">Mobile</th>
                                                    <th scope="col">IP Address</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Address</th>
                                                    <th scope="col"><i class="ri-logout-box-r-line"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><i class="bi bi-phone align-baseline me-1"></i> iPhone 12 Pro
                                                    </td>
                                                    <td>192.44.234.160</td>
                                                    <td>18 Dec, 2023</td>
                                                    <td>Los Angeles, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>

                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-tablet align-baseline me-1"></i> Apple iPad Pro
                                                    </td>
                                                    <td>192.44.234.162</td>
                                                    <td>03 Jan, 2023</td>
                                                    <td>Phoenix, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-phone align-baseline me-1"></i> Galaxy S21
                                                        Ultra 5G</td>
                                                    <td>192.45.234.54</td>
                                                    <td>25 Feb, 2023</td>
                                                    <td>Washington, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-laptop align-baseline me-1"></i> Dell Inspiron
                                                        14</td>
                                                    <td>192.40.234.32</td>
                                                    <td>16 Oct, 2022</td>
                                                    <td>Phoenix, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-phone align-baseline me-1"></i> iPhone 12 Pro
                                                    </td>
                                                    <td>192.44.326.42</td>
                                                    <td>22 May, 2022</td>
                                                    <td>Conneticut, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>

                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-tablet align-baseline me-1"></i> Apple iPad Pro
                                                    </td>
                                                    <td>190.44.182.33</td>
                                                    <td>19 Nov, 2023</td>
                                                    <td>Los Angeles, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>

                                                </tr>

                                                <tr>
                                                    <td><i class="bi bi-phone align-baseline me-1"></i> Galaxy S21
                                                        Ultra 5G</td>
                                                    <td>194.44.235.87</td>
                                                    <td>30 Aug, 2022</td>
                                                    <td>Conneticut, United States</td>
                                                    <td><a href="#" class="icon-link icon-link-hover">Logout <i
                                                                class="bi bi-box-arrow-right"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="education" role="tabpanel">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Education</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div id="newlink">
                                    <div id="1">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="degreeName" class="form-label">Degree Name</label>
                                                    <input type="text" class="form-control" id="degreeName"
                                                        placeholder="Degree name">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="universityName" class="form-label">University/school
                                                        Name</label>
                                                    <input type="text" class="form-control" id="universityName"
                                                        placeholder="University/school name">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="passedYear" class="form-label">Passed Years</label>
                                                    <div class="row g-2 justify-content-center">
                                                        <div class="col-lg-5">
                                                            <select class="form-control" data-choices
                                                                data-choices-search-false name="passedYear"
                                                                id="passedYear">
                                                                <option value="">Select years</option>
                                                                <option value="Choice 1">2001</option>
                                                                <option value="Choice 2">2002</option>
                                                                <option value="Choice 3">2003</option>
                                                                <option value="Choice 4">2004</option>
                                                                <option value="Choice 5">2005</option>
                                                                <option value="Choice 6">2006</option>
                                                                <option value="Choice 7">2007</option>
                                                                <option value="Choice 8">2008</option>
                                                                <option value="Choice 9">2009</option>
                                                                <option value="Choice 10">2010</option>
                                                                <option value="Choice 11">2011</option>
                                                                <option value="Choice 12">2012</option>
                                                                <option value="Choice 13">2013</option>
                                                                <option value="Choice 14">2014</option>
                                                                <option value="Choice 15">2015</option>
                                                                <option value="Choice 16">2016</option>
                                                                <option value="Choice 17" selected>2017</option>
                                                                <option value="Choice 18">2018</option>
                                                                <option value="Choice 19">2019</option>
                                                                <option value="Choice 20">2020</option>
                                                                <option value="Choice 21">2021</option>
                                                                <option value="Choice 22">2022</option>
                                                            </select>
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-auto align-self-center">
                                                            to
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-lg-5">
                                                            <select class="form-control" data-choices
                                                                data-choices-search-false>
                                                                <option value="">Select years</option>
                                                                <option value="Choice 1">2001</option>
                                                                <option value="Choice 2">2002</option>
                                                                <option value="Choice 3">2003</option>
                                                                <option value="Choice 4">2004</option>
                                                                <option value="Choice 5">2005</option>
                                                                <option value="Choice 6">2006</option>
                                                                <option value="Choice 7">2007</option>
                                                                <option value="Choice 8">2008</option>
                                                                <option value="Choice 9">2009</option>
                                                                <option value="Choice 10">2010</option>
                                                                <option value="Choice 11">2011</option>
                                                                <option value="Choice 12">2012</option>
                                                                <option value="Choice 13">2013</option>
                                                                <option value="Choice 14">2014</option>
                                                                <option value="Choice 15">2015</option>
                                                                <option value="Choice 16">2016</option>
                                                                <option value="Choice 17">2017</option>
                                                                <option value="Choice 18">2018</option>
                                                                <option value="Choice 19">2019</option>
                                                                <option value="Choice 20" selected>2020</option>
                                                                <option value="Choice 21">2021</option>
                                                                <option value="Choice 22">2022</option>
                                                            </select>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="degreeDescription" class="form-label">Degree
                                                        Description</label>
                                                    <textarea class="form-control" id="degreeDescription" rows="3" placeholder="Enter description"></textarea>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="hstack gap-2 justify-content-end">
                                                <a class="btn btn-danger" href="javascript:deleteEl(1)">Delete</a>
                                            </div>
                                        </div>
                                        <!--end row-->
                                    </div>
                                </div>
                                <div id="newForm" style="display: none;">

                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2">
                                        <button type="submit" class="btn btn-secondary">Update</button>
                                        <a href="javascript:new_link()" class="btn btn-primary">Add New</a>
                                    </div>
                                </div>
                                <!--end col-->
                            </form>
                        </div>
                    </div>
                    <!--end tab-pane-->
                    <div class="tab-pane" id="securityPrivacy" role="tabpanel">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Security & Privacy</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 pb-2">
                                <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-md mb-1">Two-factor Authentication</h6>
                                        <p class="text-muted">Two-factor authentication is an enhanced security. Once
                                            enabled,
                                            you'll be required to give two types of identification when you log into
                                            Google
                                            Authentication and SMS are Supported.</p>
                                    </div>
                                    <div class="flex-shrink-0 ms-sm-3">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">Enable Two-factor
                                            Authentication</a>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-md mb-1">Secondary Verification</h6>
                                        <p class="text-muted">The first factor is a password and the second commonly
                                            includes a
                                            text with a code sent to your smartphone, or biometrics using your
                                            fingerprint, face, or
                                            retina.</p>
                                    </div>
                                    <div class="flex-shrink-0 ms-sm-3">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">Set up secondary
                                            method</a>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row mb-4 mb-sm-0 mt-2">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-md mb-1">Backup Codes</h6>
                                        <p class="text-muted mb-sm-0">A backup code is automatically generated for you
                                            when you
                                            turn on two-factor authentication through your iOS or Android Twitter app.
                                            You can also
                                            generate a backup code on twitter.com.</p>
                                    </div>
                                    <div class="flex-shrink-0 ms-sm-3">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">Generate backup
                                            codes</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5 class="card-title text-decoration-underline mb-3">Application Notifications:</h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex">
                                        <div class="flex-grow-1">
                                            <label for="directMessage" class="form-check-label fs-md">Direct
                                                messages</label>
                                            <p class="text-muted">Messages from people you follow</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="directMessage" checked>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mt-2">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label fs-md mb-1" for="desktopNotification">
                                                Show desktop notifications
                                            </label>
                                            <p class="text-muted">Choose the option you want as your default setting.
                                                Block a site:
                                                Next to "Not allowed to send notifications," click Add.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="desktopNotification" checked>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mt-2">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label fs-md mb-1" for="emailNotification">
                                                Show email notifications
                                            </label>
                                            <p class="text-muted"> Under Settings, choose Notifications. Under Select
                                                an account,
                                                choose the account to enable notifications for. </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="emailNotification">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mt-2">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label fs-md mb-1" for="chatNotification">
                                                Show chat notifications
                                            </label>
                                            <p class="text-muted">To prevent duplicate mobile notifications from the
                                                Gmail and Chat
                                                apps, in settings, turn off Chat notifications.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="chatNotification">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-flex mt-2">
                                        <div class="flex-grow-1">
                                            <label class="form-check-label fs-md mb-1" for="purchaesNotification">
                                                Show purchase notifications
                                            </label>
                                            <p class="text-muted">Get real-time purchase alerts to protect yourself
                                                from fraudulent
                                                charges.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="purchaesNotification">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="card-title text-decoration-underline mb-3">Delete This Account:</h5>
                                <p class="text-muted">Go to the Data & Privacy section of your profile Account. Scroll
                                    to "Your
                                    data & privacy options." Delete your Profile Account. Follow the instructions to
                                    delete your
                                    account :</p>
                                <div>
                                    <input type="password" class="form-control" id="passwordInput"
                                        placeholder="Enter your password" value="richard@321654987"
                                        style="max-width: 265px;">
                                </div>
                                <div class="hstack gap-2 mt-3">
                                    <a href="javascript:void(0);" class="btn btn-subtle-danger">Close & Delete This
                                        Account</a>
                                    <a href="javascript:void(0);" class="btn btn-light">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div>
</div>
