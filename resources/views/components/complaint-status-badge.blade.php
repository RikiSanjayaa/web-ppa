@props(['status'])

<span @class([
    'inline-flex items-center whitespace-nowrap rounded-lg border-0 px-2.5 py-1 text-xs font-medium text-white',
    'bg-red-500' => $status === \App\Models\Complaint::STATUS_MASUK,
    'bg-orange-500' => $status === \App\Models\Complaint::STATUS_DIPROSES_LP,
    'bg-amber-500' => $status === \App\Models\Complaint::STATUS_DIPROSES_LIDIK,
    'bg-blue-500' => $status === \App\Models\Complaint::STATUS_DIPROSES_SIDIK,
    'bg-green-500' => $status === \App\Models\Complaint::STATUS_TAHAP_1,
    'bg-slate-500' => !in_array($status, [
        \App\Models\Complaint::STATUS_MASUK,
        \App\Models\Complaint::STATUS_DIPROSES_LP,
        \App\Models\Complaint::STATUS_DIPROSES_LIDIK,
        \App\Models\Complaint::STATUS_DIPROSES_SIDIK,
        \App\Models\Complaint::STATUS_TAHAP_1
    ]),
])>
    {{ ucfirst($status) }}
</span>
