<?php

namespace Javaabu\StatusEvents\Tests\Enums;


enum ApplicationStatuses: string implements IsEnum
{
    use NativeEnumsTrait;

    case Draft = 'draft';
    case PendingVerification = 'pending_verification';
    case Rejected = 'rejected';
    case Incomplete = 'incomplete';
    case Processing = 'processing';
    case Cancelled = 'cancelled';
    case PendingPayment = 'pending_payment';
    case PendingApproval = 'pending_approval';
    case Complete = 'complete';

    public static function labels(): array
    {
        return [
            self::Draft->value               => __("Draft"),
            self::PendingVerification->value => __("Pending Verification"),
            self::Rejected->value            => __("Rejected"),
            self::Incomplete->value          => __("Incomplete"),
            self::Processing->value          => __("Processing"),
            self::Cancelled->value           => __("Cancelled"),
            self::PendingPayment->value      => __("Pending Payment"),
            self::PendingApproval->value     => __("Pending Approval"),
            self::Complete->value            => __("Complete"),
        ];
    }

    public static function colors(): array
    {
        return [
            self::Draft->value               => "light",
            self::PendingVerification->value => "secondary",
            self::Rejected->value            => "danger",
            self::Incomplete->value          => "warning",
            self::Processing->value          => "info",
            self::Cancelled->value           => "dark",
            self::PendingPayment->value      => "warning",
            self::PendingApproval->value     => "warning",
            self::Complete->value            => "success",
        ];
    }

    public static function statusRemarks(): array
    {
        return [
            self::Draft->value               => __("Your application is currently in draft."),
            self::PendingVerification->value => __("Your application is pending verification."),
            self::Rejected->value            => __("Your application has been rejected."),
            self::Incomplete->value          => __("Your application is incomplete."),
            self::Processing->value          => __("Your application is being processed."),
            self::Cancelled->value           => __("Your application has been cancelled."),
            self::PendingPayment->value      => __("Your application is pending payment."),
            self::PendingApproval->value     => __("Your application is pending approval."),
            self::Complete->value            => __("Your application is complete."),
        ];
    }

    public function getRemarks(): string
    {
        return self::statusRemarks()[$this->value] ?? '';
    }

    public function getColor(): string
    {
        return self::colors()[$this->value];
    }
}
