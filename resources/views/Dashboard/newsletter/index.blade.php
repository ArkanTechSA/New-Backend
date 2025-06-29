<x-layout.default :title="'Dashboard Home'">

    @push('Dashboard-styles')
    @endpush

    <div class="row">

        {{-- عمود الفورم --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">النشرة البريدية</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.newsletters.send') }}">
                        @csrf
                        <input type="hidden" name="account_type" id="hidden_account_type" value="all" />

                        {{-- لإرسال قائمة المستخدمين المختارين --}}
                        <div id="selected_users_container"></div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">عنوان الموضوع</label>
                            <input type="text" name="subject" id="subject" class="form-control"
                                placeholder="اكتب عنوان الموضوع" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">الموضوع</label>
                            <textarea name="message" id="message" class="form-control" rows="5" placeholder="اكتب محتوى الموضوع" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">إرسال</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- عمود اختيار نوع الحساب --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h5>نوع الحساب المرسل إليه</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="account_type" class="form-label">اختر النوع</label>
                            <select name="account_type" id="account_type" class="form-select" required>
                                <option value="all">الكل</option>
                                <option value="providers">مقدمو الخدمة</option>
                                <option value="requesters">طالبي الخدمة</option>
                                <option value="newsletter">القائمة البريدية</option>
                                <option value="provider_only">مقدم خدمة</option>
                                <option value="requester_only">طالب خدمة</option>
                                <option value="provider_requester">مقدم وطالب خدمة</option>
                            </select>
                        </div>

                        <div id="users_list_container" style="display:none;">
                            <div class="mb-2">
                                <input type="text" id="user_search_input" class="form-control"
                                    placeholder="ابحث بالاسم أو الإيميل أو رقم الهاتف">
                            </div>

                            <label class="form-label">اختر المستخدمين</label>
                            <div id="users_list"
                                style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('Dashboard-scripts')
        <script>
            const accountTypeSelect = document.getElementById('account_type');
            const hiddenAccountType = document.getElementById('hidden_account_type');
            const usersListContainer = document.getElementById('users_list_container');
            const usersList = document.getElementById('users_list');
            const searchInput = document.getElementById('user_search_input');

            const providersData = @json($providers);
            const requestersData = @json($requesters);

            const usersData = {
                provider_only: providersData,
                requester_only: requestersData,
                provider_requester: [...providersData, ...requestersData],
            };

            let currentUsers = []; // نحتفظ بالمجموعة الحالية

            function renderUsersCheckboxes(type, search = '') {
                usersList.innerHTML = '';
                if (!usersData[type]) {
                    usersListContainer.style.display = 'none';
                    return;
                }

                usersListContainer.style.display = 'block';
                currentUsers = usersData[type];

                // لو في بحث، فلتر البيانات
                const filteredUsers = currentUsers.filter(user => {
                    const keyword = search.toLowerCase();
                    return (
                        (user.first_name && user.first_name.toLowerCase().includes(keyword)) ||
                        (user.email && user.email.toLowerCase().includes(keyword)) ||
                        (user.mobile_number && user.mobile_number.includes(keyword))
                    );
                });

                if (filteredUsers.length === 0) {
                    usersList.innerHTML = '<p class="text-muted">لا يوجد نتائج مطابقة.</p>';
                    return;
                }

                filteredUsers.forEach(user => {
                    const checkboxId = 'user_checkbox_' + user.id;
                    const div = document.createElement('div');
                    div.classList.add('form-check');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'users[]';
                    checkbox.value = user.id;
                    checkbox.classList.add('form-check-input');
                    checkbox.id = checkboxId;

                    const label = document.createElement('label');
                    label.classList.add('form-check-label');
                    label.setAttribute('for', checkboxId);
                    label.innerText = `${user.first_name} - ${user.email} - ${user.mobile_number ?? ''}`;

                    div.appendChild(checkbox);
                    div.appendChild(label);
                    usersList.appendChild(div);
                });
            }

            accountTypeSelect.addEventListener('change', function() {
                hiddenAccountType.value = this.value;

                if (['provider_only', 'requester_only', 'provider_requester'].includes(this.value)) {
                    renderUsersCheckboxes(this.value);
                } else {
                    usersListContainer.style.display = 'none';
                    usersList.innerHTML = '';
                }
            });

            document.getElementById('user_search_input').addEventListener('input', function() {
                const type = accountTypeSelect.value;
                if (['provider_only', 'requester_only', 'provider_requester'].includes(type)) {
                    renderUsersCheckboxes(type, this.value);
                }
            });

            hiddenAccountType.value = accountTypeSelect.value;


            document.querySelector('form[action="{{ route('admin.newsletters.send') }}"]').addEventListener('submit', function(
                e) {
                const selectedUsersContainer = document.getElementById('selected_users_container');
                selectedUsersContainer.innerHTML = ''; // نظّف أي بيانات قديمة

                // جبنا كل الشيك بوكسات المتأشر عليها في users_list
                const checkedUsers = usersList.querySelectorAll('input[type="checkbox"]:checked');

                checkedUsers.forEach(originalCheckbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'users[]';
                    hiddenInput.value = originalCheckbox.value;
                    selectedUsersContainer.appendChild(hiddenInput);
                });
            });
        </script>
    @endpush

</x-layout.default>
