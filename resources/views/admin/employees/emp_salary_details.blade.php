<div class="d-flex justify-content-between mb-2">
    <h6>Employee ID: {{ $empid }}</h6>

    <a href="{{ route('admin.employees.create.salary', $empid) }}" class="btn btn-sm btn-success">
        + Add Salary
    </a>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Year</th>
            <th>Salary Basis</th>
            <th>Payment Type</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($salaries as $salary)
            <tr>
                <td>{{ $salary->employee->name ?? '-' }}</td>
                <td>{{ $salary->year ?? '-' }}</td>
                <td>{{ $salary->salary_basis }}</td>
                <td>{{ $salary->payment_type }}</td>
                <td>{{ $salary->salary_amount }}</td>
                <td>
                    <a href="{{ route('admin.employees.edit.salary', $salary->id) }}" class="btn btn-sm btn-primary">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Data Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
