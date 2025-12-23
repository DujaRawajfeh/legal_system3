<?php $__env->startSection('title', 'لوحة رئيس القسم'); ?>

<?php $__env->startSection('chief-extra'); ?>

<!-- تغيير نص الهيدر لرئيس القسم -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const userInfo = document.querySelector('.navbar .user-info div');
    if (userInfo) {
        const userName = "<?php echo e(auth()->user()->full_name ?? 'مستخدم'); ?>";
        userInfo.textContent = `رئيس القسم / ${userName}`;
    }
});
</script>

<!-- إضافة عناصر القائمة الخاصة برئيس القسم في قائمة الجلسات -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const sessionsTrigger = document.getElementById('sessions-trigger');
    if (sessionsTrigger && sessionsTrigger.nextElementSibling) {
        const sessionsMenu = sessionsTrigger.nextElementSibling;
        
        // إضافة "تحديد القضاة للكاتب/الطابعة"
        const defineJudgeLi = document.createElement('li');
        defineJudgeLi.innerHTML = '<a href="#" onclick="openWindow(\'define\')">تحديد القضاة للكاتب/الطابعة</a>';
        sessionsMenu.appendChild(defineJudgeLi);
        
        // إضافة "تحويل الدعوى من هيئة الى اخرى"
        const transformLi = document.createElement('li');
        transformLi.innerHTML = '<a href="#" onclick="openWindow(\'transform\')">تحويل الدعوى من هيئة الى اخرى</a>';
        sessionsMenu.appendChild(transformLi);
    }
});

// ⭐ تحميل الكتاب (دالة عامة)
function loadWriters() {  
    axios.get("/chief/employees?role=writer")  
    .then(res => {  
        let users = res.data.users;  
        let select = document.getElementById("writerSelect");  

        select.innerHTML = "";  
        users.forEach(u => {  
            select.innerHTML += `<option value="${u.id}">${u.full_name}</option>`;  
        });  
    })  
    .catch(err => { 
        console.error("❌ ERROR loadWriters:", err);
        alert("❌ خطأ أثناء تحميل الكتاب"); 
    });
}

// ⭐ تحميل الطابعات (دالة عامة)
function loadTypists() {  
    axios.get("/chief/employees?role=typist")  
    .then(res => {  
        let users = res.data.users;  
        let select = document.getElementById("typistSelect");  

        select.innerHTML = "";  
        users.forEach(u => {  
            select.innerHTML += `<option value="${u.id}">${u.full_name}</option>`;  
        });  
    })  
    .catch(err => { 
        console.error("❌ ERROR loadTypists:", err);
        alert("❌ خطأ أثناء تحميل الطابعات");
    });
}

// ⭐ تحميل القضاة (دالة عامة)
function loadJudges() {  
    axios.get("/chief/judges")  
    .then(res => {  
        let judges = res.data.judges;  

        let wS = document.getElementById("writerJudgeSelect");  
        let tS = document.getElementById("typistJudgeSelect");  

        wS.innerHTML = "";  
        tS.innerHTML = "";  

        judges.forEach(j => {  
            wS.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;  
            tS.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;  
        });  
    })  
    .catch(err => { 
        console.error("❌ ERROR loadJudges:", err);
        alert("❌ خطأ أثناء تحميل القضاة"); 
    });
}

// دالة فتح النوافذ
function openWindow(type) {
    if (type === 'define') {
        const modal = new bootstrap.Modal(document.getElementById('assignJudgeModal'));
        modal.show();
        
        // تحميل الموظفين والقضاة
        loadWriters();
        loadTypists();
        loadJudges();
    } else if (type === 'transform') {
        const modal = new bootstrap.Modal(document.getElementById('transferCaseModal'));
        modal.show();
        
        // تحميل القضاة
        axios.get("/chief/judges")
            .then(res => {
                let judges = res.data.judges;
                let currentSelect = document.getElementById("current_judge");
                let newSelect = document.getElementById("new_judge");
                
                currentSelect.innerHTML = `<option value="">اختر القاضي الحالي...</option>`;
                newSelect.innerHTML = `<option value="">اختر القاضي الجديد...</option>`;
                
                judges.forEach(j => {
                    currentSelect.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;
                    newSelect.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;
                });
            })
            .catch(() => {
                alert("❌ خطأ أثناء جلب القضاة");
            });
    }
}
</script>

<!-- إضافة زر تحويل القضايا داخل قائمة الدعوى/الطلب فقط لرئيس القسم -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const menu = document.getElementById('writer-case-options');

    // نتأكد أنه رئيس القسم (الصفحة هذه أصلا لرئيس القسم)
    if (menu) {

        // إنشاء عنصر جديد للقائمة
        let li = document.createElement("li");
        li.id = "open-transfer-case";
        li.style.padding = "10px";
        li.style.borderBottom = "1px solid #ddd";
        li.style.cursor = "pointer";
        li.textContent = "تحويل الدعاوى من هيئة إلى أخرى";

        // إضافته داخل القائمة
        menu.querySelector("ul").appendChild(li);
    }
});
</script>


<!-- ⭐ نافذة تحويل الدعوى -->
<style>
#transferCaseModal label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
    margin-bottom: 5px;
}

#transferCaseModal input,
#transferCaseModal select {
    padding: 8px 10px;
    border: 1px solid #bfc3c7;
    border-radius: 8px;
    font-size: 14px;
    width: 100%;
}

#transferCaseModal input:focus,
#transferCaseModal select:focus {
    border-color: #000;
    outline: none;
    box-shadow: 0 0 3px rgba(0,0,0,0.4);
}

#transferCaseModal .btn-area {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}

#transferCaseModal .btn-save {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: 0;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
}

#transferCaseModal .btn-close-modal {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: 0;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
}
</style>

<div class="modal fade" id="transferCaseModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">تحويل الدعوى من هيئة إلى أخرى</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- رقم الدعوى -->
        <label>رقم الدعوى:</label>
        <input type="text" id="transfer_case_number" placeholder="أدخل رقم الدعوى">

        <!-- الهيئة الحالية -->
        <label>الهيئة الحالية:</label>
        <select id="current_judge">
          <option value="">اختر القاضي الحالي...</option>
        </select>

        <!-- الهيئة الجديدة -->
        <label>الهيئة الجديدة:</label>
        <select id="new_judge">
          <option value="">اختر القاضي الجديد...</option>
        </select>

        <!-- أزرار -->
        <div class="btn-area">
          <button class="btn-save" id="save_transfer">حفظ التحويل</button>
          <button class="btn-close-modal" data-bs-dismiss="modal">انهاء</button>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
    //نافذه تحويل دعوى

document.addEventListener("DOMContentLoaded", function () {

    // فتح نافذة التحويل من القائمة
    document.addEventListener("click", function(e){
        if (e.target && e.target.id === "open-transfer-case") {

            let modal = new bootstrap.Modal(document.getElementById("transferCaseModal"));
            modal.show();

            // تحميل القضاة
            axios.get("/chief/judges")
            .then(res => {
                let judges = res.data.judges;

                let currentSelect = document.getElementById("current_judge");
                let newSelect     = document.getElementById("new_judge");

                currentSelect.innerHTML = `<option value="">اختر القاضي الحالي...</option>`;
                newSelect.innerHTML     = `<option value="">اختر القاضي الجديد...</option>`;

                judges.forEach(j => {
                    currentSelect.innerHTML += `<option value="${j.id}">${j.full_name}</option>`;
                    newSelect.innerHTML     += `<option value="${j.id}">${j.full_name}</option>`;
                });
            })
            .catch(() => {
                alert("❌ خطأ أثناء جلب القضاة");
            });
        }
    });

    // حفظ التحويل
    document.getElementById("save_transfer").addEventListener("click", () => {

        let currentJudge = document.getElementById("current_judge").value;
        let newJudge     = document.getElementById("new_judge").value;
        let number       = document.getElementById("transfer_case_number").value;

        if (!currentJudge || !newJudge || !number) {
            alert("⚠️ يرجى تعبئة جميع الحقول");
            return;
        }

        axios.post("/chief/transfer-case", {
            case_number: number,
            old_judge_id: currentJudge,
            new_judge_id: newJudge
        })
        .then(() => {
            alert("✔ تم تحويل الدعوى بنجاح");
        })
        .catch(() => {
            alert("❌ حدث خطأ أثناء التحويل");
        });

    });

});
</script>







<!--  زر تعيين قاضي داخل قائمة الدعوى/الطلب -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const menu = document.getElementById('writer-case-options');

    if (menu) {

        let li = document.createElement("li");
        li.id = "open-assign-judge";
        li.style.padding = "10px";
        li.style.borderBottom = "1px solid #ddd";
        li.style.cursor = "pointer";
        li.textContent = "تعيين قاضي للكاتب / الطابعة";

        menu.querySelector("ul").appendChild(li);
    }

});
</script>
<!--  نافذة تحديد القضاة للكاتب / الطابعة -->
<style>
#assignJudgeModal .judge-tabs {
    display: flex;
    border-bottom: 2px solid #ddd;
    margin-bottom: 20px;
}

#assignJudgeModal .judge-tab {
    padding: 10px 20px;
    cursor: pointer;
    font-weight: bold;
    border-bottom: 3px solid transparent;
    background: none;
    border: none;
    color: #555;
}

#assignJudgeModal .judge-tab.active {
    border-bottom-color: #000;
    color: #000;
}

#assignJudgeModal .judge-tab-content {
    display: none;
}

#assignJudgeModal .judge-tab-content.active {
    display: block;
}

#assignJudgeModal label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
}

#assignJudgeModal select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #bfc3c7;
    border-radius: 8px;
    background-color: #fff;
    font-size: 14px;
}

#assignJudgeModal .btn-area {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}

#assignJudgeModal .btn-save {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: 0;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
}

#assignJudgeModal .btn-close-modal {
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    border: 0;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 13px;
}
</style>

<div class="modal fade" id="assignJudgeModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">تحديد القضاة للكاتب / الطابعة</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Tabs -->
        <div class="judge-tabs">
            <button class="judge-tab active" onclick="switchJudgeTab('writer')">كاتب</button>
            <button class="judge-tab" onclick="switchJudgeTab('typist')">طابعة</button>
        </div>

        <!-- Writer Tab -->
        <div id="writerTabContent" class="judge-tab-content active">
            <label>اختر الكاتب:</label>
            <select id="writerSelect"></select>

            <label>اختر القاضي:</label>
            <select id="writerJudgeSelect"></select>

            <div class="btn-area">
                <button class="btn-save" id="saveWriterJudge">حفظ</button>
                <button class="btn-close-modal" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>

        <!-- Typist Tab -->
        <div id="typistTabContent" class="judge-tab-content">
            <label>اختر الطابعة:</label>
            <select id="typistSelect"></select>

            <label>اختر القاضي:</label>
            <select id="typistJudgeSelect"></select>

            <div class="btn-area">
                <button class="btn-save" id="saveTypistJudge">حفظ</button>
                <button class="btn-close-modal" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>

      </div>

    </div>
  </div>
</div>
<script>  
// Tab switching function
function switchJudgeTab(tabName) {
    document.querySelectorAll('#assignJudgeModal .judge-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#assignJudgeModal .judge-tab-content').forEach(c => c.classList.remove('active'));

    document.querySelector(`#assignJudgeModal .judge-tab[onclick="switchJudgeTab('${tabName}')"]`).classList.add('active');
    
    if (tabName === 'writer') {
        document.getElementById('writerTabContent').classList.add('active');
    } else {
        document.getElementById('typistTabContent').classList.add('active');
    }
}

document.addEventListener("DOMContentLoaded", () => {  

    // ⭐ فتح نافذة تعيين القاضي من القائمة  
    document.addEventListener("click", function(e){  
        if (e.target && e.target.id === "open-assign-judge") {  

            let modal = new bootstrap.Modal(document.getElementById("assignJudgeModal"));  
            modal.show();  

            // تحميل الموظفين والقضاة  
            loadWriters();  
            loadTypists();  
            loadJudges();  
        }  
    });  

    // ⭐ حفظ القاضي للكاتب  
    document.getElementById("saveWriterJudge").addEventListener("click", () => {  

        axios.post("/chief/assign-judge", {  
            employee_id: document.getElementById("writerSelect").value,  
            judge_id: document.getElementById("writerJudgeSelect").value  
        })  
        .then(() => alert("✔ تم حفظ القاضي للكاتب"))  
        .catch(err => {
            console.error("❌ ERROR saveWriterJudge RESPONSE:", err.response);
            console.error("❌ ERROR saveWriterJudge DATA:", err.response?.data);
            alert("❌ خطأ أثناء الحفظ");
        });
    });  

    // ⭐ حفظ القاضي للطابعة  
    document.getElementById("saveTypistJudge").addEventListener("click", () => {  

        axios.post("/chief/assign-judge", {  
            employee_id: document.getElementById("typistSelect").value,  
            judge_id: document.getElementById("typistJudgeSelect").value  
        })  
        .then(() => alert("✔ تم حفظ القاضي للطابعة"))  
        .catch(err => {
            console.error("❌ ERROR saveTypistJudge RESPONSE:", err.response);
            console.error("❌ ERROR saveTypistJudge DATA:", err.response?.data);
            alert("❌ خطأ أثناء الحفظ");
        });
    });  

});  
</script>








<!-- جدول الموقوفين -->
<div class="mt-4 ">
    <div class="card-header text-black">
        <h4 class="mb-0 pb-4">الموقوفون المنتهية فترة توقيفهم أو قاربت على الانتهاء</h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle" id="detainedTable">
                <thead class="table-dark">
                    <tr>
                        <th>اسم الموقوف</th>
                        <th>تاريخ التوقيف</th>
                        <th>مدة التوقيف (يوم)</th>
                        <th>تاريخ انتهاء التوقيف</th>
                        <th>المدة المتبقية</th>
                        <th>رقم الدعوى</th>
                        <th>الدعوى التي سببت التوقيف</th>
                    </tr>
                </thead>

                <tbody id="detainedBody">
                    <tr>
                        <td colspan="7">جاري التحميل...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function loadDetainedTable() {
        axios.get("/chief/detained-list")
            .then(res => {
                let data = res.data.data;
                let tbody = document.querySelector("#detainedBody");
                tbody.innerHTML = "";

                data.forEach(row => {

                    // 🔵 تحديد اللون حسب حالة المدة
                    let color = "black";
                    if (row.remaining_days < 0) color = "red";
                    else if (row.remaining_days <= 3) color = "orange";

                    // 🔵 تعبئة الصفوف
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.participant_name}</td>
                            <td>${row.start_date}</td>
                            <td>${row.duration}</td>
                            <td>${row.end_date}</td>

                            <td style="color:${color}; font-weight:bold;">
                                ${
                                    row.remaining_days < 0
                                    ? "منتهٍ"
                                    : Math.floor(row.remaining_days) + " يوم"
                                }
                            </td>

                            <td>${row.case_number}</td>
                            <td>${row.case_type}</td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error(err);
                alert("❌ خطأ أثناء تحميل بيانات الموقوفين");
            });
    }

    // 🔵 تحميل الجدول عند فتح الصفحة
    loadDetainedTable();

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('clerk_dashboard.writer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\legal_system\resources\views/clerk_dashboard/chief.blade.php ENDPATH**/ ?>