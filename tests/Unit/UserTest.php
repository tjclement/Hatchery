<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Models\Vote;
use App\Models\Warning;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UserTest.
 *
 * @author annejan@badge.team
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Assert the User has a relation with zero or more Project(s).
     */
    public function testUserProjectRelationship(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertEmpty($user->projects);
        /** @var Project $project */
        $project = factory(Project::class)->create(['user_id' => $user->id]);
        /** @var User $user */
        $user = User::find($user->id);
        $this->assertCount(1, $user->projects);
        $this->assertInstanceOf(Collection::class, $user->projects);
        $this->assertInstanceOf(Project::class, $user->projects->first());
        /** @var Project $userProject */
        $userProject = $user->projects->first();
        $this->assertEquals($project->id, $userProject->id);
        factory(Project::class)->create(['user_id' => $user->id]);
        /** @var User $user */
        $user = User::find($user->id);
        $this->assertCount(2, $user->projects);
    }

    /**
     * Assert the User has a relation with zero or more Votes(s).
     */
    public function testUserVotesRelationship(): void
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertEmpty($user->votes);
        /** @var User $user */
        $user = User::find($user->id);
        /** @var Vote $vote */
        $vote = factory(Vote::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, $user->votes);
        $this->assertInstanceOf(Collection::class, $user->votes);
        $this->assertInstanceOf(Vote::class, $user->votes->first());
        /** @var Vote $userVote */
        $userVote = $user->votes->first();
        $this->assertEquals($vote->id, $userVote->id);
        factory(Vote::class)->create(['user_id' => $user->id]);
        /** @var User $user */
        $user = User::find($user->id);
        $this->assertCount(2, $user->votes);
    }

    /**
     * Assert the User has a relation with zero or more Warnings(s).
     */
    public function testUserWarningsRelationship(): void
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertEmpty($user->votes);
        /** @var User $user */
        $user = User::find($user->id);
        $warning = factory(Warning::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, $user->warnings);
        $this->assertInstanceOf(Collection::class, $user->votes);
        $this->assertInstanceOf(Warning::class, $user->warnings->first());
        /** @var Warning $userWarning */
        $userWarning = $user->warnings->first();
        $this->assertEquals($warning->id, $userWarning->id);
        factory(Warning::class)->create(['user_id' => $user->id]);
        /** @var User $user */
        $user = User::find($user->id);
        $this->assertCount(2, $user->warnings);
    }

    /**
     * Assert the User has a collaborator relation with Projects.
     */
    public function testUserProjectsRelationship(): void
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $this->be($user);
        $project = factory(Project::class)->create();
        $this->assertEmpty($otherUser->collaborations);
        $project->collaborators()->attach($otherUser);
        /** @var User $otherUser */
        $otherUser = User::find($otherUser->id);
        $this->assertInstanceOf(Collection::class, $otherUser->collaborations);
        $this->assertInstanceOf(Project::class, $otherUser->collaborations->first());
        /** @var Project $collaboration */
        $collaboration = $otherUser->collaborations->first();
        $this->assertEquals($project->id, $collaboration->id);
        $this->assertCount(1, $otherUser->collaborations);
        $otherProject = factory(Project::class)->create();
        $otherProject->collaborators()->attach($otherUser);
        /** @var User $otherUser */
        $otherUser = User::find($otherUser->id);
        $this->assertCount(2, $otherUser->collaborations);
    }
}
